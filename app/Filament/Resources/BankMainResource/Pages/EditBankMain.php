<?php

namespace App\Filament\Resources\BankMainResource\Pages;

use App\Filament\Resources\BankMainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankMain extends EditRecord
{
    protected static string $resource = BankMainResource::class;
    protected ?string $heading='';


}
