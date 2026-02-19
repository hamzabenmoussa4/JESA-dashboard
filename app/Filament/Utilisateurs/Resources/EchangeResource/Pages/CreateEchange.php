<?php

namespace App\Filament\Utilisateurs\Resources\EchangeResource\Pages;

use App\Filament\Utilisateurs\Resources\EchangeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEchange extends CreateRecord
{
    protected static string $resource = EchangeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $interlocuteur = \App\Models\Interlocuteur::find($data['interlocuteur_id']);
        abort_unless($interlocuteur && $interlocuteur->user_id === Auth::id(), 403);
        return $data;
    }
}
