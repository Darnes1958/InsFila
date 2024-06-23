<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class inp_kst extends Page
{
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->hasAnyPermission('ادخال اقساط','تعديل اقساط','الغاء اقساط');
  }
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationLabel='أقساط';

    protected static string $view = 'filament.pages.inp_kst';
    protected static ?int $navigationSort = 1;


  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class="leading-3 h-0 text-sm py-0">ادخال أقساط</div>');
  }

}
