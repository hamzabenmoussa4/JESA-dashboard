<?php

namespace App\Filament\Utilisateurs\Resources\MinuteResource\Pages;

use App\Filament\Utilisateurs\Resources\MinuteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinutes extends ListRecords
{
    protected static string $resource = MinuteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Minute'),
        ];
    }
}
