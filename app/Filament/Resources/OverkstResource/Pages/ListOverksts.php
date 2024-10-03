<?php

namespace App\Filament\Resources\OverkstResource\Pages;

use App\Filament\Resources\OverkstResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOverksts extends ListRecords
{
    protected static string $resource = OverkstResource::class;
    protected ?string $heading='أقساط مخصومة بالفائض';


}
