<?php

namespace App\Filament\Utilisateurs\Resources\EchangeResource\Pages;

use App\Filament\Utilisateurs\Resources\EchangeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditEchange extends EditRecord
{
    protected static string $resource = EchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        abort_unless($this->record->interlocuteur->user_id === Auth::id(), 403);
    }
}
