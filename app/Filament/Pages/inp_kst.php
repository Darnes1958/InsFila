<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class inp_kst extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel='ادخال وتعديل أقساط';
    protected static ?string $navigationGroup='أقساط';
    protected static string $view = 'filament.pages.inp_kst';

  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class="leading-3 h-0 text-sm py-0">ادخال أقساط</div>');
  }

}
