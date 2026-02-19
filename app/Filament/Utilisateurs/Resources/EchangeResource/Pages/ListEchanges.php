<?php

namespace App\Filament\Utilisateurs\Resources\EchangeResource\Pages;

use App\Filament\Utilisateurs\Resources\EchangeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEchanges extends ListRecords
{
    protected static string $resource = EchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
