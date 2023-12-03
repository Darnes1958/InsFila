<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class mainRep extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.main-rep';
    protected static ?string $pluralModelLabel='تقرير عن عقد';
    public static ?string $title = 'تقارير عن عقد';
    protected static ?string $navigationGroup='تقارير';

  /**
   * @param int|null $navigationGroupSort
   */
  public static function setNavigationGroupSort(?int $navigationGroupSort): void
  {
    self::$navigationGroupSort = $navigationGroupSort;
  }


  protected static ?int $navigationGroupSort ;


  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class=" text-base text-primary-400">استفسار عن عقد</div>');
  }
}
