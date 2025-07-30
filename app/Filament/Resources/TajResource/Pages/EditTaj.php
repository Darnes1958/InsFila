<?php

namespace App\Filament\Resources\TajResource\Pages;

use App\Filament\Resources\TajResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaj extends EditRecord
{
    protected static string $resource = TajResource::class;
    protected ?string $heading='';

}
