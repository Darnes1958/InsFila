<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class mainArcRep extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.main-arc-rep';
    protected static ?string $pluralModelLabel='تقرير عن عقد من الأرشيف';
    public static ?string $title = ' تقارير عن عقد من الأرشيف';
    protected static ?string $navigationGroup='تقارير';


  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class=" text-base text-primary-400">استفسار عن عقد من الارشيف</div>');
  }
}
