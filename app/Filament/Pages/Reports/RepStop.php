<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;

class RepStop extends Page
{
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
  public static ?string $title = 'ايقاف الخصم';

  protected static ?string $navigationGroup='تقارير';
  protected static ?int $navigationSort=5;


  protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.rep-stop';
}
