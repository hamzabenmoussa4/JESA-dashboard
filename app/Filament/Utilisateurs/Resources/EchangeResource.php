<?php


// namespace App\Filament\Utilisateurs\Resources;

// use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
// use App\Filament\Utilisateurs\Resources\EchangeResource\Pages;
// use App\Models\Echange;
// use App\Models\Interlocuteur;
// use Filament\Forms;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Facades\Auth;

// class EchangeResource extends Resource
// {
//     protected static ?string $model = Echange::class;

//     protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
//     protected static ?string $navigationLabel = 'Exchanges';
//     protected static ?string $pluralModelLabel = 'Exchanges';
//     protected static ?string $modelLabel = 'Exchange';

//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\Select::make('interlocuteur_id')
//                     ->label('Contact')
//                     ->required()
//                     ->options(fn () => Interlocuteur::where('user_id', Auth::id())
//                         ->pluck('nom', 'id'))
//                     ->searchable(),

//                 Forms\Components\Select::make('type')
//                     ->label('Type')
//                     ->required()
//                     // valeurs FR conservées, libellés EN affichés
//                     ->options([
//                         'appel'   => 'Call',
//                         'email'   => 'Email',
//                         'réunion' => 'Meeting',
//                     ])
//                     ->native(false),

//                 Forms\Components\Textarea::make('contenu')
//                     ->label('Content')
//                     ->required()
//                     ->rows(5),

//                 Forms\Components\DateTimePicker::make('date_echange')
//                     ->label('Exchange date & time')
//                     ->required(),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('id')
//                     ->label('ID')
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),

//                 Tables\Columns\TextColumn::make('interlocuteur.nom')
//                     ->label('Contact')
//                     ->sortable()
//                     ->searchable(),

//                 Tables\Columns\TextColumn::make('type')
//                     ->label('Type')
//                     ->badge()
//                     // couleurs selon la valeur FR stockée
//                     ->color(fn (string $state): string => match ($state) {
//                         'appel' => 'info',
//                         'email' => 'success',
//                         'réunion' => 'warning',
//                         default => 'gray',
//                     })
//                     // libellé affiché en anglais
//                     ->formatStateUsing(fn (string $state): string => match ($state) {
//                         'appel' => 'Call',
//                         'email' => 'Email',
//                         'réunion' => 'Meeting',
//                         default => ucfirst($state),
//                     }),

//                 Tables\Columns\TextColumn::make('contenu')
//                     ->label('Content')
//                     ->limit(50)
//                     ->wrap()
//                     ->searchable()
//                     ->toggleable(),

//                 Tables\Columns\TextColumn::make('date_echange')
//                     ->label('Exchange date')
//                     ->dateTime('d/m/Y H:i')
//                     ->sortable(),

//                 Tables\Columns\TextColumn::make('created_at')
//                     ->label('Created at')
//                     ->dateTime('d/m/Y')
//                     ->sortable(),

//                 Tables\Columns\TextColumn::make('updated_at')
//                     ->label('Updated at')
//                     ->dateTime('d/m/Y H:i')
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->filters([
//                 Tables\Filters\SelectFilter::make('type')
//                     ->label('Exchange type')
//                     // valeurs FR conservées, libellés EN
//                     ->options([
//                         'appel'   => 'Call',
//                         'email'   => 'Email',
//                         'réunion' => 'Meeting',
//                     ]),
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make()->label('Edit'),
//                 Tables\Actions\DeleteAction::make()->label('Delete'),
//             ])
//             ->bulkActions([
//                 Tables\Actions\DeleteBulkAction::make(),
//                 FilamentExportBulkAction::make('export_echanges')
//                     ->label('Export exchanges')
//                     ->fileName('user_exchanges')
//                     ->withColumns([
//                         Tables\Columns\TextColumn::make('id')->label('ID'),
//                         Tables\Columns\TextColumn::make('interlocuteur.nom')->label('Contact'),
//                         Tables\Columns\TextColumn::make('type')
//                             ->label('Type')
//                             ->formatStateUsing(fn (string $state): string => match ($state) {
//                                 'appel' => 'Call',
//                                 'email' => 'Email',
//                                 'réunion' => 'Meeting',
//                                 default => ucfirst($state),
//                             }),
//                         Tables\Columns\TextColumn::make('contenu')->label('Content'),
//                         Tables\Columns\TextColumn::make('date_echange')->label('Exchange date'),
//                         Tables\Columns\TextColumn::make('created_at')->label('Created at'),
//                         Tables\Columns\TextColumn::make('updated_at')->label('Updated at'),
//                     ]),
//             ])
//             ->defaultSort('date_echange', 'desc');
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index'  => Pages\ListEchanges::route('/'),
//             'create' => Pages\CreateEchange::route('/create'),
//             'edit'   => Pages\EditEchange::route('/{record}/edit'),
//         ];
//     }

//     /**
//      * User can only see exchanges linked to their contacts.
//      */
//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()->whereHas('interlocuteur', function ($query) {
//             $query->where('user_id', Auth::id());
//         });
//     }
// }
namespace App\Filament\Utilisateurs\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Utilisateurs\Resources\EchangeResource\Pages;
use App\Models\Echange;
use App\Models\Interlocuteur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class EchangeResource extends Resource
{
    protected static ?string $model = Echange::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'Exchanges';
    protected static ?string $pluralModelLabel = 'Exchanges';
    protected static ?string $modelLabel = 'Exchange';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('interlocuteur_id')
                    ->label('Contact')
                    ->required()
                    ->options(fn () => Interlocuteur::where('user_id', Auth::id())
                        ->pluck('nom', 'id'))
                    ->searchable(),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->required()
                    ->options([
                        'appel'   => 'Call',
                        'email'   => 'Email',
                        'réunion' => 'Meeting',
                    ])
                    ->native(false),

                Forms\Components\Textarea::make('contenu')
                    ->label('Content')
                    ->required()
                    ->rows(5),

                Forms\Components\DateTimePicker::make('date_echange')
                    ->label('Exchange date & time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Action "View" : modale avec contenu complet
        $viewAction = Tables\Actions\Action::make('view')
            ->label('View')
            ->icon('heroicon-o-eye')
            ->modalWidth('2xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->modalHeading(fn (Echange $record) =>
                'Exchange — ' . ($record->interlocuteur->nom ?? 'Contact')
            )
            ->modalContent(function (Echange $record) {
                // Type en anglais depuis la valeur FR stockée
                $typeEn = match ($record->type) {
                    'appel'   => 'Call',
                    'email'   => 'Email',
                    'réunion' => 'Meeting',
                    default   => ucfirst((string) $record->type),
                };

                // Sécuriser le formatage de la date (string ou Carbon)
                $dateText = '';
                if (!empty($record->date_echange)) {
                    try {
                        $dateText = Carbon::parse($record->date_echange)->format('d/m/Y H:i');
                    } catch (\Throwable $e) {
                        // si parse impossible, on affiche tel quel
                        $dateText = (string) $record->date_echange;
                    }
                }

                $html  = '<div style="display:grid;gap:.75rem;font-size:.95rem;line-height:1.6">';
                $html .= '<div><strong>Type:</strong> ' . e($typeEn) . '</div>';
                if ($dateText !== '') {
                    $html .= '<div><strong>Date:</strong> ' . e($dateText) . '</div>';
                }
                $html .= '<hr style="opacity:.2;margin:.75rem 0" />';
                $html .= '<div style="white-space:pre-wrap;">' . nl2br(e($record->contenu)) . '</div>';
                $html .= '</div>';

                return new HtmlString($html);
            });

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('interlocuteur.nom')
                    ->label('Contact')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'appel'   => 'info',
                        'email'   => 'success',
                        'réunion' => 'warning',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'appel'   => 'Call',
                        'email'   => 'Email',
                        'réunion' => 'Meeting',
                        default   => ucfirst($state),
                    }),

                // Extrait cliquable -> ouvre la modale "View"
                Tables\Columns\TextColumn::make('contenu')
                    ->label('Content')
                    ->limit(50)
                    ->wrap()
                    ->searchable()
                    ->tooltip('Click to view full content')
                    ->extraAttributes(['class' => 'cursor-pointer underline underline-offset-2'])
                    ->action($viewAction),

                Tables\Columns\TextColumn::make('date_echange')
                    ->label('Exchange date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Exchange type')
                    ->options([
                        'appel'   => 'Call',
                        'email'   => 'Email',
                        'réunion' => 'Meeting',
                    ]),
            ])
            ->actions([
                $viewAction, // bouton View
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export_echanges')
                    ->label('Export exchanges')
                    ->fileName('user_exchanges')
                    ->withColumns([
                        Tables\Columns\TextColumn::make('id')->label('ID'),
                        Tables\Columns\TextColumn::make('interlocuteur.nom')->label('Contact'),
                        Tables\Columns\TextColumn::make('type')
                            ->label('Type')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'appel'   => 'Call',
                                'email'   => 'Email',
                                'réunion' => 'Meeting',
                                default   => ucfirst($state),
                            }),
                        Tables\Columns\TextColumn::make('contenu')->label('Content'),
                        Tables\Columns\TextColumn::make('date_echange')->label('Exchange date'),
                        Tables\Columns\TextColumn::make('created_at')->label('Created at'),
                        Tables\Columns\TextColumn::make('updated_at')->label('Updated at'),
                    ]),
            ])
            ->defaultSort('date_echange', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEchanges::route('/'),
            'create' => Pages\CreateEchange::route('/create'),
            'edit'   => Pages\EditEchange::route('/{record}/edit'),
        ];
    }

    /**
     * User can only see exchanges linked to their contacts.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('interlocuteur', function ($query) {
            $query->where('user_id', Auth::id());
        });
    }
}
