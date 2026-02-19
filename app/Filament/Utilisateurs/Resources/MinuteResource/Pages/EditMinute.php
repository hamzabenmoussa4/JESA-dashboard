<?php

namespace App\Filament\Utilisateurs\Resources\MinuteResource\Pages;

use App\Filament\Utilisateurs\Resources\MinuteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinute extends EditRecord
{
    protected static string $resource = MinuteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
