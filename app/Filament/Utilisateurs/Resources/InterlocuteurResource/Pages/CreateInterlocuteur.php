<?php

namespace App\Filament\Utilisateurs\Resources\InterlocuteurResource\Pages;

use App\Filament\Utilisateurs\Resources\InterlocuteurResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInterlocuteur extends CreateRecord
{
    protected static string $resource = InterlocuteurResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // Set the user_id to the currently authenticated user
        return $data;
    }
}
