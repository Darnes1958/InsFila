<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DamegCont extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static string $view = 'filament.pages.dameg-cont';

    protected ?string $heading = '';
    public function getBreadcrumbs(): array
    {
        return [""];
    }
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('ضم عقد');
  }
    protected static ?string $navigationLabel='ضم عقد';
    protected static ?int $navigationSort = 2;
}
