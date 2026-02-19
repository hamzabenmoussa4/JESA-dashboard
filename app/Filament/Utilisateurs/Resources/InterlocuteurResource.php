<?php

namespace App\Filament\Utilisateurs\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Utilisateurs\Resources\InterlocuteurResource\Pages;
use App\Models\Interlocuteur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InterlocuteurResource extends Resource
{
    protected static ?string $model = Interlocuteur::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'My Contacts';
    protected static ?string $pluralModelLabel = 'Contacts';
    protected static ?string $modelLabel = 'Contact';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\TextInput::make('telephone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(20),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable(),

                Tables\Columns\TextColumn::make('telephone')
                    ->label('Phone')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('email_only')
                    ->label('With email')
                    ->query(fn ($query) => $query->whereNotNull('email')),

                Tables\Filters\Filter::make('recent')
                    ->label('Recently added')
                    ->query(fn ($query) => $query->orderByDesc('created_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('export_interlocuteurs')
                        ->label('Export contacts')
                        ->fileName('user_contacts'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Add relation to exchanges later if you want
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInterlocuteurs::route('/'),
            'create' => Pages\CreateInterlocuteur::route('/create'),
            'edit'   => Pages\EditInterlocuteur::route('/{record}/edit'),
        ];
    }

    /**
     * Restrict contacts to the authenticated user.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }
}
