<?php

namespace App\Filament\Resources\TajResource\Pages;

use App\Filament\Resources\TajResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTajs extends ListRecords
{
    protected static string $resource = TajResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة'),
        ];
    }
}
