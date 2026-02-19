<?php

namespace App\Filament\Utilisateurs\Resources\InterlocuteurResource\Pages;

use App\Filament\Utilisateurs\Resources\InterlocuteurResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditInterlocuteur extends EditRecord
{
    protected static string $resource = InterlocuteurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        abort_unless($this->record->user_id === Auth::id(), 403);
    }
}
