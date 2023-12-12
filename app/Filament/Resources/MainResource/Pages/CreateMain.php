<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use function Filament\Support\get_model_label;

class CreateMain extends CreateRecord
{
    protected static string $resource = MainResource::class;

  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }

  protected function getRedirectUrl(): string
  {
    return $this->previousUrl ?? $this->getResource()::getUrl('list');
  }
}
