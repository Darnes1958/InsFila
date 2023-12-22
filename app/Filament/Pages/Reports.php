<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;


class Reports extends Page
{

  protected ?string $heading = '';
  public function getBreadcrumbs(): array
   {
    return [""];
   }
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('تقرير عن مصرف');
  }

    public static ?string $title = 'تقرير عن مصرف';

    protected static ?string $navigationGroup='تقارير';
  protected static ?int $navigationSort=4;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';
}
