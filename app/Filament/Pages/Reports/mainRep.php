<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class mainRep extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.main-rep';
  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class=" text-base text-primary-400">استفسار عن عقد</div>');
  }
}
