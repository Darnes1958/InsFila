<?php

namespace App\Filament\Resources\DateofexcelResource\Pages;

use App\Filament\Resources\DateofexcelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDateofexcel extends EditRecord
{
    protected static string $resource = DateofexcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
