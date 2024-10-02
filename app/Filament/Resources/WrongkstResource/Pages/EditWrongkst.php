<?php

namespace App\Filament\Resources\WrongkstResource\Pages;

use App\Filament\Resources\WrongkstResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrongkst extends EditRecord
{
    protected static string $resource = WrongkstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
