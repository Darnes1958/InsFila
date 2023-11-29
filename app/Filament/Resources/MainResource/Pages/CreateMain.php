<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class CreateMain extends CreateRecord
{
    protected static string $resource = MainResource::class;

    public function getTitle():  string|Htmlable
    {
        return  new HtmlString('<div class="leading-3 h-0 text-md mb-2  text-primary-400">ادخال عقود</div>');
    }
}
