<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class mainRep extends Page
{
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }


    protected static string $view = 'filament.pages.reports.main-rep';

  protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static ?string $title = 'تقرير عن عقد';
    protected static ?string $navigationGroup='تقارير';
    protected static ?int $navigationSort=1;



}
