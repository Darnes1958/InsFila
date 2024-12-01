<?php

namespace App\Filament\Resources\HafithaResource\Pages;

use App\Filament\Resources\HafithaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHafithas extends ListRecords
{
    protected static string $resource = HafithaResource::class;
    protected ?string $heading='الحوافظ';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
