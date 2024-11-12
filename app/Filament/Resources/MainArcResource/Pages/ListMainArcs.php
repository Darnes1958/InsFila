<?php

namespace App\Filament\Resources\MainArcResource\Pages;

use App\Filament\Resources\MainArcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListMainArcs extends ListRecords
{
    protected static string $resource = MainArcResource::class;

    protected ?string $heading='استفسار عن الأرشيف';

}
