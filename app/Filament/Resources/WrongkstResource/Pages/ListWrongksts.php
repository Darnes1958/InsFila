<?php

namespace App\Filament\Resources\WrongkstResource\Pages;

use App\Filament\Resources\WrongkstResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrongksts extends ListRecords
{
    protected static string $resource = WrongkstResource::class;

    protected ?string $heading='أقساط واردة بالخطأ';
}
