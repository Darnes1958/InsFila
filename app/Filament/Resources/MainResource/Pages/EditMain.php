<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditMain extends EditRecord
{
    protected static string $resource = MainResource::class;
  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-primary-400 py-0">تعديل عقود</div>');
  }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
