<?php

namespace App\Filament\Resources\TarKstResource\Pages;

use App\Filament\Resources\TarKstResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTarKst extends EditRecord
{
    protected static string $resource = TarKstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
