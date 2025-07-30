<?php

namespace App\Livewire\widgets;

use App\Enums\Tar_type;
use App\Livewire\Traits\AksatTrait;
use App\Livewire\Traits\MainTrait;
use App\Models\Main;
use App\Models\Sell_tran;
use App\Models\Tran;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\On;


class SelltranWidget extends BaseWidget
{

    protected static ?string $heading='';
    public $sell_id;
    #[On('Take_Main_Id')]
    public function do($main_id)
    {
        $this->sell_id=Main::find($main_id)->sell_id;
    }

    public function mount($main_id){
        $this->sell_id=Main::find($main_id)->sell_id;
    }

    public function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([5,12,15,50])

            ->query(function (Tran $tran){
                $tran=Sell_tran::where('sell_id',$this->sell_id);
                return $tran;
            })

            ->recordUrl(null)
            ->columns([
                TextColumn::make('ser')
                    ->size(TextColumnSize::ExtraSmall)
                    ->rowIndex()
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                Tables\Columns\TextColumn::make('item_id')
                    ->size(TextColumnSize::ExtraSmall)

                    ->sortable()
                    ->label('رقم الصنف'),
                Tables\Columns\TextColumn::make('Item.name')
                    ->size(TextColumnSize::ExtraSmall)

                    ->sortable()
                    ->label('اسم الصنف'),
                Tables\Columns\TextColumn::make('q1')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الكمية'),
                Tables\Columns\TextColumn::make('price1')
                    ->size(TextColumnSize::ExtraSmall)

                    ->label('السعر'),
                Tables\Columns\TextColumn::make('sub_tot')


                    ->size(TextColumnSize::ExtraSmall)
                    ->label('المجموع'),
            ])
           ;
    }
}
