<?php

namespace App\Livewire\widgets;

use App\Enums\Tar_type;
use App\Livewire\Traits\AksatTrait;
use App\Livewire\Traits\MainTrait;
use App\Models\Main;
use App\Models\Overkst;
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


class OverWidget extends BaseWidget
{

    protected static ?string $heading='';
    public $main_id;
    #[On('Take_Main_Id')]
    public function do($main_id)
    {
        $this->main_id=$main_id;
    }

    public function mount($main_id=null){
        $this->main_id=$main_id;
    }

    public function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,12,15,50])

            ->query(function (Overkst $tran){
                $tran=Overkst::where('main_id',$this->main_id);
                return $tran;
            })


            ->columns([
                TextColumn::make('id')
                    ->label('الرقم الألي'),

                TextColumn::make('overkstable.Customer.name')
                    ->label('الاسم'),

                TextColumn::make('over_date')
                    ->searchable()
                    ->sortable()
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('status')
                    ->label('الحالة'),
                TextColumn::make('overkstable_type')
                    ->label('حالة العقد'),
            ])
           ;
    }
}
