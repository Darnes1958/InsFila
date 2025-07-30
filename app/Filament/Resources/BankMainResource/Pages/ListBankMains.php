<?php

namespace App\Filament\Resources\BankMainResource\Pages;

use App\Filament\Resources\BankMainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankMains extends ListRecords
{
    protected static string $resource = BankMainResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة'),
        ];
    }
}
