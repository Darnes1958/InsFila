<?php

namespace App\Filament\Resources\BankMainResource\Pages;

use App\Filament\Resources\BankMainResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankMain extends CreateRecord
{
    protected ?string $heading='';
    protected static string $resource = BankMainResource::class;
}
