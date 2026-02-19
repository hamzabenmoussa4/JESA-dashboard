<?php

namespace App\Filament\Utilisateurs\Resources\InterlocuteurResource\Pages;

use App\Filament\Utilisateurs\Resources\InterlocuteurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterlocuteurs extends ListRecords
{
    protected static string $resource = InterlocuteurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
