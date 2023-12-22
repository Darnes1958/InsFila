<?php

namespace App\Filament\Pages;

use App\Models\Main;
use Filament\Pages\Page;

class InpStop extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';
  protected static ?string $navigationLabel='ايقاف خصم';
  protected static ?int $navigationSort = 5;
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('ايقاف الخصم');
  }
  public static function getNavigationBadge(): ?string
  {
    return Main::where('raseed','<=',0)
      ->whereNotIn('id',function ($q) {
        $q->select('main_id')->from('Stops');
      })->count();
  }
    protected static string $view = 'filament.pages.inp-stop';
}
