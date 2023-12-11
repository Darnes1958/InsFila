<?php

namespace App\Filament\Pages;

use App\Models\Overkst;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class InpTar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.inp-tar';
    protected static ?string $navigationLabel='ترجيع أقساط';
    protected static ?int $navigationSort = 3;
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }


    public function getTitle():  string|Htmlable
    {
        return  new HtmlString('<div class="leading-3 h-0 text-sm py-1 text-primary-400">ترجيع مبالغ مخصومة بالفائض</div>');
    }

  public static function getNavigationBadge(): ?string
  {
    return Overkst::where('status','غير مرجع')->count();
  }


}
