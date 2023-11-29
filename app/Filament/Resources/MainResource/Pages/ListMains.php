<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use App\Models\Customer;
use App\Models\Main;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ListMains extends ListRecords
{
    protected static string $resource = MainResource::class;



    public function getTitle():  string|Htmlable
    {
        return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-primary-400 py-0">عقود</div>');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
