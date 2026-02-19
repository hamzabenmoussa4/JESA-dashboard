<?php
namespace App\Filament\Utilisateurs\Resources;

use App\Filament\Utilisateurs\Resources\MinuteResource\Pages;
use App\Models\Minute;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class MinuteResource extends Resource
{
    protected static ?string $model = Minute::class;

    protected static ?string $navigationLabel = 'Minutes';
    protected static ?string $pluralModelLabel = 'Minutes';
    protected static ?string $modelLabel = 'Minute';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    // S’affiche sous Meetings
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Lier automatiquement la minute à l'utilisateur connecté
            Forms\Components\Hidden::make('user_id')
                ->default(fn () => Auth::id())
                ->dehydrated(true),

            // Meeting Name : liste limitée aux meetings de l'utilisateur
            Forms\Components\Select::make('meeting_id')
                ->label('Meeting Name')
                ->options(fn () =>
                    Meeting::query()
                        ->where('user_id', Auth::id())
                        ->orderBy('date_of_meeting', 'desc')
                        ->pluck('meeting_name', 'id')
                )
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Textarea::make('topic_idea_decision')
                ->label('Topic / Idea / Decision (Minutes)')
                ->rows(6)
                ->required()
                ->maxLength(5000),

            Forms\Components\TextInput::make('responsible')
                ->label('Responsible')
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('due_date')
                ->label('Due Date')
                ->required()
                ->native(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        // Action modale pour afficher tout le contexte (sans l’afficher dans la table)
        $showContextAction = Tables\Actions\Action::make('showContext')
            ->label('View')
            ->icon('heroicon-o-eye')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->modalHeading(fn (Minute $record) => 'Minutes — ' . ($record->meeting->meeting_name ?? ''))
            ->modalWidth('2xl')
            ->modalContent(fn (Minute $record) => new HtmlString(
                '<div style="white-space:pre-wrap; line-height:1.6; font-size:0.95rem;">'
                . e($record->topic_idea_decision)
                . '</div>'
            ));

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meeting.meeting_name')
                    ->label('Meeting')
                    ->searchable()
                    ->sortable(),

                // 👉 AFFICHAGE TRONQUÉ (on garde limit(50) côté UI)
                Tables\Columns\TextColumn::make('topic_idea_decision')
                    ->label('Topic / Idea / Decision')
                    ->limit(50) // ne montre pas tout dans la table
                    ->tooltip(fn ($record) => $record->topic_idea_decision)
                    ->searchable()
                    ->action($showContextAction)
                    ->extraAttributes(['class' => 'cursor-pointer underline underline-offset-2']),

                Tables\Columns\TextColumn::make('responsible')
                    ->label('Responsible')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('Y-m-d')
                    ->sortable()
                    // 🔎 Recherche “smart” : complète, année, mois/année, mois seul, jour seul
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $parts = self::parseDateSearch($search);

                        if ($parts['exact'] !== null) {
                            return $query->orWhereDate('due_date', $parts['exact']);
                        }
                        if ($parts['year'] !== null) {
                            $query->orWhereYear('due_date', $parts['year']);
                        }
                        if ($parts['month'] !== null && $parts['year'] !== null) {
                            $query->orWhere(function (Builder $q) use ($parts) {
                                $q->whereYear('due_date', $parts['year'])
                                  ->whereMonth('due_date', $parts['month']);
                            });
                        }
                        if ($parts['month_only'] !== null) {
                            $query->orWhereMonth('due_date', $parts['month_only']);
                        }
                        if ($parts['day_only'] !== null) {
                            $query->orWhereDay('due_date', $parts['day_only']);
                        }

                        return $query;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // UX barre de recherche
            ->searchPlaceholder('Search by meeting, context, responsible, or date (YYYY-MM-DD, DD/MM/YYYY, YYYY, MM/YYYY, MM, DD)')
            ->searchDebounce(600)

            ->filters([
                Tables\Filters\Filter::make('due_on')
                    ->label('Due on (exact date)')
                    ->form([
                        Forms\Components\DatePicker::make('date')->native(false)->label('Date'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $query->when($data['date'] ?? null, fn ($q, $date) =>
                            $q->whereDate('due_date', $date)
                        )
                    ),

                Tables\Filters\Filter::make('due_between')
                    ->label('Due between')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false)->label('From'),
                        Forms\Components\DatePicker::make('until')->native(false)->label('Until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $query
                            ->when($data['from'] ?? null, fn ($q, $date) =>
                                $q->whereDate('due_date', '>=', $date)
                            )
                            ->when($data['until'] ?? null, fn ($q, $date) =>
                                $q->whereDate('due_date', '<=', $date)
                            )
                    ),

                Tables\Filters\Filter::make('due_soon')
                    ->label('Due in next 7 days')
                    ->query(fn (Builder $q) =>
                        $q->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                    ),

                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn (Builder $q) =>
                        $q->where('due_date', '<', now()->toDateString())
                    ),
            ])

            ->actions([
                $showContextAction,
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            // ✅ Export comme dans Exchanges (plugin FilamentExport)
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

                FilamentExportBulkAction::make('export_minutes')
                    ->label('Export minutes')
                    ->fileName('user_minutes')
                    ->withColumns([
                        Tables\Columns\TextColumn::make('meeting.meeting_name')->label('Meeting'),
                        Tables\Columns\TextColumn::make('topic_idea_decision')->label('Topic / Idea / Decision'),
                        Tables\Columns\TextColumn::make('responsible')->label('Responsible'),
                        Tables\Columns\TextColumn::make('due_date')->label('Due Date'),
                        Tables\Columns\TextColumn::make('created_at')->label('Created at'),
                        Tables\Columns\TextColumn::make('updated_at')->label('Updated at'),
                    ]),
            ])

            ->defaultSort('due_date', 'asc');
    }

    public static function getEloquentQuery(): Builder
    {
        // Restreindre aux minutes de l'utilisateur connecté + éviter N+1
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->with(['meeting']);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMinutes::route('/'),
            'create' => Pages\CreateMinute::route('/create'),
            'edit'   => Pages\EditMinute::route('/{record}/edit'),
        ];
    }

    /**
     * Parse la saisie utilisateur pour la recherche par date.
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
            try { $out['exact'] = Carbon::createFromFormat('Y-m-d', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
        }

        // DD/MM/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $s)) {
            try { $out['exact'] = Carbon::createFromFormat('d/m/Y', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
        }

        // DD-MM-YYYY
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $s)) {
            try { $out['exact'] = Carbon::createFromFormat('d-m-Y', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
        }

        // YYYY (année seule)
        if (preg_match('/^\d{4}$/', $s)) {
            $year = (int) $s;
            if ($year >= 1970 && $year <= 2100) { $out['year'] = $year; }
        }

        // MM/YYYY ou MM-YYYY
        if (preg_match('/^(0?[1-9]|1[0-2])[\/-](\d{4})$/', $s, $m)) {
            $month = (int) $m[1]; $year = (int) $m[2];
            if ($year >= 1970 && $year <= 2100) { $out['month'] = $month; $out['year'] = $year; }
        }

        // Mois seul (1..12) OU Jour seul (1..31)
        if (preg_match('/^\d{1,2}$/', $s)) {
            $num = (int) $s;
            if ($num >= 1 && $num <= 12) { $out['month_only'] = $num; $out['day_only'] = $num; }
            elseif ($num >= 13 && $num <= 31) { $out['day_only'] = $num; }
        }

        return $out;
    }
}
// namespace App\Filament\Utilisateurs\Resources;

// use App\Filament\Utilisateurs\Resources\MinuteResource\Pages;
// use App\Models\Minute;
// use App\Models\Meeting;
// use Barryvdh\DomPDF\Facade\Pdf;              // ⬅️ DomPDF
// use Carbon\Carbon;
// use Filament\Forms;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Response;
// use Illuminate\Support\Str;
// use Illuminate\Support\HtmlString;

// class MinuteResource extends Resource
// {
//     protected static ?string $model = Minute::class;

//     protected static ?string $navigationLabel = 'Minutes';
//     protected static ?string $pluralModelLabel = 'Minutes';
//     protected static ?string $modelLabel = 'Minute';
//     protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
//     protected static ?int $navigationSort = 50;

//     public static function form(Form $form): Form
//     {
//         return $form->schema([
//             Forms\Components\Hidden::make('user_id')
//                 ->default(fn () => Auth::id())
//                 ->dehydrated(true),

//             Forms\Components\Select::make('meeting_id')
//                 ->label('Meeting Name')
//                 ->options(fn () =>
//                     Meeting::query()
//                         ->where('user_id', Auth::id())
//                         ->orderBy('date_of_meeting', 'desc')
//                         ->pluck('meeting_name', 'id')
//                 )
//                 ->searchable()
//                 ->preload()
//                 ->required(),

//             Forms\Components\Textarea::make('topic_idea_decision')
//                 ->label('Topic / Idea / Decision (Minutes)')
//                 ->rows(6)
//                 ->required()
//                 ->maxLength(5000),

//             Forms\Components\TextInput::make('responsible')
//                 ->label('Responsible')
//                 ->required()
//                 ->maxLength(255),

//             Forms\Components\DatePicker::make('due_date')
//                 ->label('Due Date')
//                 ->required()
//                 ->native(false),
//         ])->columns(2);
//     }

//     public static function table(Table $table): Table
//     {
//         $showContextAction = Tables\Actions\Action::make('showContext')
//             ->label('View')
//             ->icon('heroicon-o-eye')
//             ->modalSubmitAction(false)
//             ->modalCancelActionLabel('Close')
//             ->modalHeading(fn (Minute $record) => 'Minutes — ' . ($record->meeting->meeting_name ?? ''))
//             ->modalWidth('2xl')
//             ->modalContent(fn (Minute $record) => new HtmlString(
//                 '<div style="white-space:pre-wrap; line-height:1.6; font-size:0.95rem;">'
//                 . e($record->topic_idea_decision)
//                 . '</div>'
//             ));

//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('meeting.meeting_name')
//                     ->label('Meeting')
//                     ->searchable()
//                     ->sortable(),

//                 // Affichage tronqué dans le tableau, mais export = texte complet
//                 Tables\Columns\TextColumn::make('topic_idea_decision')
//                     ->label('Topic / Idea / Decision')
//                     ->limit(50)
//                     ->tooltip(fn ($record) => $record->topic_idea_decision)
//                     ->searchable()
//                     ->action($showContextAction)
//                     ->extraAttributes(['class' => 'cursor-pointer underline underline-offset-2']),

//                 Tables\Columns\TextColumn::make('responsible')
//                     ->label('Responsible')
//                     ->searchable()
//                     ->sortable(),

//                 Tables\Columns\TextColumn::make('due_date')
//                     ->label('Due Date')
//                     ->date('Y-m-d')
//                     ->sortable()
//                     ->searchable(query: function (Builder $query, string $search): Builder {
//                         $parts = self::parseDateSearch($search);

//                         if ($parts['exact'] !== null) {
//                             return $query->orWhereDate('due_date', $parts['exact']);
//                         }
//                         if ($parts['year'] !== null) {
//                             $query->orWhereYear('due_date', $parts['year']);
//                         }
//                         if ($parts['month'] !== null && $parts['year'] !== null) {
//                             $query->orWhere(function (Builder $q) use ($parts) {
//                                 $q->whereYear('due_date', $parts['year'])
//                                   ->whereMonth('due_date', $parts['month']);
//                             });
//                         }
//                         if ($parts['month_only'] !== null) {
//                             $query->orWhereMonth('due_date', $parts['month_only']);
//                         }
//                         if ($parts['day_only'] !== null) {
//                             $query->orWhereDay('due_date', $parts['day_only']);
//                         }

//                         return $query;
//                     }),

//                 Tables\Columns\TextColumn::make('created_at')
//                     ->since()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->searchPlaceholder('Search by meeting, context, responsible, or date (YYYY-MM-DD, DD/MM/YYYY, YYYY, MM/YYYY, MM, DD)')
//             ->searchDebounce(600)

//             ->filters([
//                 Tables\Filters\Filter::make('due_on')
//                     ->label('Due on (exact date)')
//                     ->form([
//                         Forms\Components\DatePicker::make('date')->native(false)->label('Date'),
//                     ])
//                     ->query(fn (Builder $query, array $data): Builder =>
//                         $query->when($data['date'] ?? null, fn ($q, $date) =>
//                             $q->whereDate('due_date', $date)
//                         )
//                     ),

//                 Tables\Filters\Filter::make('due_between')
//                     ->label('Due between')
//                     ->form([
//                         Forms\Components\DatePicker::make('from')->native(false)->label('From'),
//                         Forms\Components\DatePicker::make('until')->native(false)->label('Until'),
//                     ])
//                     ->query(fn (Builder $query, array $data): Builder =>
//                         $query
//                             ->when($data['from'] ?? null, fn ($q, $date) =>
//                                 $q->whereDate('due_date', '>=', $date)
//                             )
//                             ->when($data['until'] ?? null, fn ($q, $date) =>
//                                 $q->whereDate('due_date', '<=', $date)
//                             )
//                     ),

//                 Tables\Filters\Filter::make('due_soon')
//                     ->label('Due in next 7 days')
//                     ->query(fn (Builder $q) =>
//                         $q->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
//                     ),

//                 Tables\Filters\Filter::make('overdue')
//                     ->label('Overdue')
//                     ->query(fn (Builder $q) =>
//                         $q->where('due_date', '<', now()->toDateString())
//                     ),
//             ])

//             ->actions([
//                 $showContextAction,
//                 Tables\Actions\EditAction::make(),
//                 Tables\Actions\DeleteAction::make(),
//             ])

//             // ✅ Une seule méthode d’export : PDF DomPDF avec vue Blade — texte complet
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),

//                     Tables\Actions\BulkAction::make('export_selected_pdf')
//                         ->label('Export Selected (PDF)')
//                         ->icon('heroicon-o-document-text')
//                         ->action(function (Collection $records) {
//                             $minutes = Minute::with('meeting')
//                                 ->where('user_id', Auth::id())
//                                 ->whereIn('id', $records->pluck('id'))
//                                 ->orderBy('due_date')
//                                 ->get();

//                             $file = 'minutes-selected-' . now()->format('Y-m-d_H-i-s') . '.pdf';

//                             $pdf = Pdf::loadView('exports.minutes-selected-pdf', [
//                                 'records'     => $minutes,
//                                 'generatedAt' => now(),
//                                 'user'        => Auth::user(),
//                             ])->setPaper('a4', 'portrait');

//                             return Response::streamDownload(function () use ($pdf) {
//                                 echo $pdf->output();
//                             }, $file, [
//                                 'Content-Type' => 'application/pdf',
//                                 'Cache-Control' => 'no-store, no-cache, must-revalidate',
//                             ]);
//                         }),
//                 ]),
//             ])

//             ->defaultSort('due_date', 'asc');
//     }

//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()
//             ->where('user_id', Auth::id())
//             ->with(['meeting']);
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index'  => Pages\ListMinutes::route('/'),
//             'create' => Pages\CreateMinute::route('/create'),
//             'edit'   => Pages\EditMinute::route('/{record}/edit'),
//         ];
//     }

//     private static function parseDateSearch(string $search): array
//     {
//         $s = trim($search);

//         $out = [
//             'exact'      => null,
//             'year'       => null,
//             'month'      => null,
//             'month_only' => null,
//             'day_only'   => null,
//         ];

//         if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
//             try { $out['exact'] = Carbon::createFromFormat('Y-m-d', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
//         }

//         if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $s)) {
//             try { $out['exact'] = Carbon::createFromFormat('d/m/Y', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
//         }

//         if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $s)) {
//             try { $out['exact'] = Carbon::createFromFormat('d-m-Y', $s)->format('Y-m-d'); return $out; } catch (\Throwable $e) {}
//         }

//         if (preg_match('/^\d{4}$/', $s)) {
//             $year = (int) $s;
//             if ($year >= 1970 && $year <= 2100) { $out['year'] = $year; }
//         }

//         if (preg_match('/^(0?[1-9]|1[0-2])[\/-](\d{4})$/', $s, $m)) {
//             $month = (int) $m[1]; $year = (int) $m[2];
//             if ($year >= 1970 && $year <= 2100) { $out['month'] = $month; $out['year'] = $year; }
//         }

//         if (preg_match('/^\d{1,2}$/', $s)) {
//             $num = (int) $s;
//             if ($num >= 1 && $num <= 12) { $out['month_only'] = $num; $out['day_only'] = $num; }
//             elseif ($num >= 13 && $num <= 31) { $out['day_only'] = $num; }
//         }

//         return $out;
//     }
// }
