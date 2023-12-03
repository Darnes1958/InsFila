<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use App\Models\Main;
use App\Models\Tran;
use App\Services\MainForm;
use Filament\Actions;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewMain extends ViewRecord
{
    protected static string $resource = MainResource::class;
    public function getTitle():  string|Htmlable
    {
        return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-primary-400 py-0">استفسار عن عقد</div>');
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Group::make([
                  Section::make(new HtmlString('<div class="text-danger-600">بيانات الزبون</div>'))

                    ->schema([

                          TextEntry::make('Customer.CusName')
                            ->label(new HtmlString('<div class="text-primary-400 text-lg">اسم الزبون</div>'))
                            ->color('info')->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),
                          TextEntry::make('Bank.BankName')
                            ->label('المصرف')
                            ->color('info')
                            ->columnSpanFull(),
                          TextEntry::make('acc')->label('رقم الحساب')
                           ->color('info')->columnSpan(2),
                          TextEntry::make('libyana')->label('لبيانا')
                           ->color('Fuchsia'),
                          TextEntry::make('mdar')->label('المدار')->color('grean')
                           ->color('green'),


                      ])
                ]),
                Group::make([
                  Section::make('بيانات العقد')

                    ->schema([
                      TextEntry::make('id')
                        ->columnSpanFull()
                        ->label(new HtmlString('<div class="text-primary-400 text-lg">رقم العقد</div>'))
                        ->color('info')
                        ->size(TextEntry\TextEntrySize::Large),
                          TextEntry::make('sul')->label('قيمة العقد'),
                          TextEntry::make('sul_begin')->label('تاريخ العقد'),
                          TextEntry::make('kst_count')->label('عدد الأقساط'),
                          TextEntry::make('kst')->label('القسط'),
                          TextEntry::make('pay')->label('المدفوع'),
                          TextEntry::make('raseed')->label('المتبقي'),
                        ])->columns(3)
                ]),


            ]);
    }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Tran $tran)  {
         $tran=Main::all();
        return  $tran;
      })
      ->columns([
        TextColumn::make('kst_date')
          ->label('تاريخ القسط'),
        TextColumn::make('ksm_date')
          ->label('تاريخ الخصم'),
        TextColumn::make('ksm')
          ->label('القسط')

      ]);



  }
}
