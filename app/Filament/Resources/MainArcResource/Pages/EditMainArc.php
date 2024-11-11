<?php

namespace App\Filament\Resources\MainArcResource\Pages;

use App\Filament\Resources\MainArcResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMainArc extends EditRecord
{
    protected static string $resource = MainArcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
