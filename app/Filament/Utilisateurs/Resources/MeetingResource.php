<?php

namespace App\Filament\Utilisateurs\Resources;

use App\Filament\Utilisateurs\Resources\MeetingResource\Pages;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Carbon\Carbon;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationLabel = 'Meetings';
    protected static ?string $pluralModelLabel = 'Meetings';
    protected static ?string $modelLabel = 'Meeting';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    // ordre d’affichage sous "Mes interlocuteurs"
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // lie le meeting à l'utilisateur connecté
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id())
                    ->dehydrated(true),

                Forms\Components\TextInput::make('meeting_name')
                    ->label('Meeting Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('date_of_meeting')
                    ->label('Date of Meeting')
                    ->required()
                    ->native(false),

                Forms\Components\TimePicker::make('time')
                    ->label('Time')
                    ->required()
                    ->seconds(false),

                Forms\Components\TextInput::make('prepared_by')
                    ->label('Prepared by')
                    ->default(fn () => Auth::user()?->name)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->maxLength(255),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meeting_name')
                    ->label('Meeting')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_of_meeting')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable()
                    // 🔎 recherche "smart" sur l’input global
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $parts = self::parseDateSearch($search);

                        // 1) Date complète
                        if ($parts['exact'] !== null) {
                            return $query->orWhereDate('date_of_meeting', $parts['exact']);
                        }

                        // 2) Année seule
                        if ($parts['year'] !== null) {
                            $query->orWhereYear('date_of_meeting', $parts['year']);
                        }

                        // 3) Mois + Année
                        if ($parts['month'] !== null && $parts['year'] !== null) {
                            $query->orWhere(function (Builder $q) use ($parts) {
                                $q->whereYear('date_of_meeting', $parts['year'])
                                  ->whereMonth('date_of_meeting', $parts['month']);
                            });
                        }

                        // 4) Mois seul
                        if ($parts['month_only'] !== null) {
                            $query->orWhereMonth('date_of_meeting', $parts['month_only']);
                        }

                        // 5) Jour seul
                        if ($parts['day_only'] !== null) {
                            $query->orWhereDay('date_of_meeting', $parts['day_only']);
                        }

                        return $query;
                    }),

                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->sortable()
                    // 🔎 recherche "smart" time: HH:MM, HH seul, :MM (minute seule)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $ts = self::parseTimeSearch($search);

                        // HH:MM exact
                        if ($ts['hhmm']) {
                            return $query->orWhereTime('time', $ts['hhmm']);
                        }

                        // Heure seule
                        if ($ts['hour'] !== null) {
                            // WHERE HOUR(time) = ?
                            $hour = sprintf('%02d', $ts['hour']);
                            $query->orWhereRaw('TIME_FORMAT(`time`, "%H") = ?', [$hour]);
                        }

                        // Minute seule
                        if ($ts['minute'] !== null) {
                            $min = sprintf('%02d', $ts['minute']);
                            $query->orWhereRaw('TIME_FORMAT(`time`, "%i") = ?', [$min]);
                        }

                        return $query;
                    }),

                Tables\Columns\TextColumn::make('prepared_by')
                    ->label('Prepared by')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->label('Created')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // UX barre de recherche
            ->searchPlaceholder('Search meeting, prepared by, location, date (YYYY-MM-DD, DD/MM/YYYY, YYYY, MM/YYYY, MM, DD) or time (HH:MM, HH, :MM)')
            ->searchDebounce(600)

            ->filters([
                // Date exacte
                Tables\Filters\Filter::make('on_date')
                    ->label('On date')
                    ->form([
                        Forms\Components\DatePicker::make('date')->native(false)->label('Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'] ?? null,
                            fn ($q, $date) => $q->whereDate('date_of_meeting', $date)
                        );
                    }),

                // Plage de dates
                Tables\Filters\Filter::make('between_dates')
                    ->label('Between dates')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false)->label('From'),
                        Forms\Components\DatePicker::make('until')->native(false)->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) =>
                                $q->whereDate('date_of_meeting', '>=', $date)
                            )
                            ->when($data['until'] ?? null, fn ($q, $date) =>
                                $q->whereDate('date_of_meeting', '<=', $date)
                            );
                    }),

                // Plage d’heures (ex: 09:00 -> 12:00)
                Tables\Filters\Filter::make('between_times')
                    ->label('Between times')
                    ->form([
                        Forms\Components\TimePicker::make('from')->seconds(false)->label('From'),
                        Forms\Components\TimePicker::make('until')->seconds(false)->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from  = $data['from']  ?? null;
                        $until = $data['until'] ?? null;

                        return $query
                            ->when($from, fn ($q) => $q->where('time', '>=', $from))
                            ->when($until, fn ($q) => $q->where('time', '<=', $until));
                    }),

                // Aujourd’hui
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $q) => $q->whereDate('date_of_meeting', now()->toDateString())),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('export'),
                ]),
            ])

            ->defaultSort('date_of_meeting', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        // limite aux données de l'utilisateur connecté
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit'   => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }

    /**
     * Parse la saisie libre pour la date (YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY, YYYY, MM/YYYY, MM, DD).
     * Retourne:
     * - exact (Y-m-d|string|null)
     * - year (int|null)
     * - month (int|null)  // avec year (MM/YYYY)
     * - month_only (int|null)
     * - day_only (int|null)
     */
    private static function parseDateSearch(string $search): array
    {
        $s = trim($search);

        $out = [
            'exact'      => null,
            'year'       => null,
            'month'      => null,
            'month_only' => null,
            'day_only'   => null,
        ];

        // YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
            try {
                $out['exact'] = Carbon::createFromFormat('Y-m-d', $s)->format('Y-m-d');
                return $out;
            } catch (\Throwable $e) {}
        }

        // DD/MM/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $s)) {
            try {
                $out['exact'] = Carbon::createFromFormat('d/m/Y', $s)->format('Y-m-d');
                return $out;
            } catch (\Throwable $e) {}
        }

        // DD-MM-YYYY
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $s)) {
            try {
                $out['exact'] = Carbon::createFromFormat('d-m-Y', $s)->format('Y-m-d');
                return $out;
            } catch (\Throwable $e) {}
        }

        // YYYY (année seule)
        if (preg_match('/^\d{4}$/', $s)) {
            $year = (int) $s;
            if ($year >= 1970 && $year <= 2100) {
                $out['year'] = $year;
            }
        }

        // MM/YYYY ou MM-YYYY
        if (preg_match('/^(0?[1-9]|1[0-2])[\/-](\d{4})$/', $s, $m)) {
            $month = (int) $m[1];
            $year  = (int) $m[2];
            if ($year >= 1970 && $year <= 2100) {
                $out['month'] = $month;
                $out['year']  = $year;
            }
        }

        // Mois seul (1..12) OU Jour seul (1..31)
        if (preg_match('/^\d{1,2}$/', $s)) {
            $num = (int) $s;
            if ($num >= 1 && $num <= 12) {
                $out['month_only'] = $num;
                $out['day_only']   = $num; // accepte aussi comme jour (ex: "5")
            } elseif ($num >= 13 && $num <= 31) {
                $out['day_only'] = $num;
            }
        }

        return $out;
    }

    /**
     * Parse la saisie libre pour l’heure:
     * - HH:MM  → 'hhmm' => 'HH:MM'
     * - HH     → 'hour' => int
     * - :MM    → 'minute' => int
     */
    private static function parseTimeSearch(string $search): array
    {
        $s = trim($search);

        $out = [
            'hhmm'   => null,
            'hour'   => null,
            'minute' => null,
        ];

        // HH:MM
        if (preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $s)) {
            $parts = explode(':', $s);
            $h = (int) $parts[0];
            $m = (int) $parts[1];
            $out['hhmm'] = sprintf('%02d:%02d', $h, $m);
            return $out;
        }

        // Heure seule (0..23)
        if (preg_match('/^(?:[01]?\d|2[0-3])$/', $s)) {
            $out['hour'] = (int) $s;
        }

        // Minute seule ":MM"
        if (preg_match('/^:([0-5]\d)$/', $s, $m)) {
            $out['minute'] = (int) $m[1];
        }

        return $out;
    }
}
