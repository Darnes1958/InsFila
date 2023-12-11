<?php

namespace App\Filament\Pages;

use App\Models\Main;
use Filament\Pages\Page;

class InpStop extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $navigationLabel='ايقاف خصم';
  protected static ?int $navigationSort = 4;
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
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
