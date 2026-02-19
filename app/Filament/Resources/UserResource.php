<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\SelectFilter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $label = 'User';
    protected static ?string $pluralLabel = 'Users';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                // TextInput::make('email')
                //     ->label('Email')
                //     ->email()
                //     ->required()
                //     ->maxLength(255),
                TextInput::make('email')
    ->label('Email')
    ->email()
    ->required()
    ->maxLength(255)
    ->unique(table: \App\Models\User::class, column: 'email', ignoreRecord: true) // ✅ uniqueness
    ->validationMessages([
        'unique' => 'This email is already in use.',
    ])
    ->live(onBlur: true), // (optional) shows the error right after leaving the field

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),

                Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->required()
                    // On traduit uniquement à l’affichage
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return match ($record->name) {
                            'administrateur' => 'Admin',
                            'utilisateur'    => 'User',
                            default          => ucfirst($record->name),
                        };
                    }),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                fn () => User::query()->where('email', '!=', 'admin@admin.com')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'administrateur' => 'Admin',
                        'utilisateur'    => 'User',
                        default          => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created at'),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    // idem : traduire affichage des options
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return match ($record->name) {
                            'administrateur' => 'Admin',
                            'utilisateur'    => 'User',
                            default          => ucfirst($record->name),
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export_users')
                    ->label('Export Users'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }
}
