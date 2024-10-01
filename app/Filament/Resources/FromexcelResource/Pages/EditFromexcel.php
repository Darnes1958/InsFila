<?php

namespace App\Filament\Resources\FromexcelResource\Pages;

use App\Filament\Resources\FromexcelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFromexcel extends EditRecord
{
    protected static string $resource = FromexcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
