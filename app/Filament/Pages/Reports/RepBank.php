<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;

class RepBank extends Page
{
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
   public static ?string $title = 'إجمالي المصارف';

  protected static ?string $navigationGroup='تقارير';
  protected static ?int $navigationSort=3;

  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('اجمالي المصارف');
  }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.rep-bank';
}
