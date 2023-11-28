<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
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
                Grid::make([
                    'default' => 2,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                    'xl' => 6,
                    '2xl' => 8,
                ]),
                Section::make('بيانات الزبون')
                    ->description('عرض لبيانات الزبون')
                    ->schema([
                        TextEntry::make('Customer.CusName')
                        ->columnSpan(2),
                        TextEntry::make('Bank.BankName'),
                        TextEntry::make('acc'),


                        TextEntry::make('libyana'),
                        TextEntry::make('mdar'),

                    ]),
                Section::make('بيانات العقد')
                    ->description('عرض لبيانات العقد')
                    ->schema([

                        TextEntry::make('sul')->columnSpan(2),
                        TextEntry::make('kst_count'),
                        TextEntry::make('kst'),
                        TextEntry::make('pay'),
                        TextEntry::make('raseed'),

                    ]),
            ]);
    }
}
