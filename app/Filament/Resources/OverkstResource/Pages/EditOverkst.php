<?php

namespace App\Filament\Resources\OverkstResource\Pages;

use App\Filament\Resources\OverkstResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOverkst extends EditRecord
{
    protected static string $resource = OverkstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
