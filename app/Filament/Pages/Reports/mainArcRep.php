<?php

namespace App\Filament\Pages\Reports;

use App\Models\Main_arc;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class mainArcRep extends Page
{
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.main-arc-rep';
    protected static ?string $pluralModelLabel='تقرير عن عقد من الأرشيف';
    public static ?string $title = ' تقارير عن عقد من الأرشيف';
    protected static ?string $navigationGroup='تقارير';
  protected static ?int $navigationSort=2;
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('تقرير عن عقد من الارشيف') && Main_arc::count()>0 ;
  }
  public static function getNavigationBadge(): ?string
  {
    return Main_arc::count();
  }
  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class=" text-base text-primary-400">استفسار عن عقد من الارشيف</div>');
  }
}
