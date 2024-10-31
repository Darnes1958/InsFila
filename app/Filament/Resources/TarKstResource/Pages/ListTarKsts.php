<?php

namespace App\Filament\Resources\TarKstResource\Pages;

use App\Filament\Resources\TarKstResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTarKsts extends ListRecords
{
    protected static string $resource = TarKstResource::class;

    protected ?string $heading='';


}
