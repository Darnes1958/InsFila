<?php

namespace App\Livewire\Reports;

use App\Models\Main;
use Livewire\Component;
use App\Models\Tran;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Filament\Forms\Get;


class RepAksatNotGet extends Component implements HasTable, HasForms
{
    use InteractsWithTable,InteractsWithForms;
    #[Reactive]
    public $bank_id;
    #[Reactive]
    public $Date1;
    #[Reactive]
    public $Date2;
    #[Reactive]
    public $By;


    public function table(Table $table):Table
    {
        return $table
            ->query(function (Main $main)  {
                if ($this->By==1)
                 $main= Main::where('bank_id',$this->bank_id)
                  ->whereNotin('id',function ($q){
                    $q->select('main_id')->from('trans')->whereBetween('ksm_date',[$this->Date1,$this->Date2]);
                 });
                if ($this->By==2)
                    $main= Main::whereIn('bank_id',function ($q){
                                  $q->select('id')->from('banks')->where('taj_id',$this->bank_id);
                                 })
                        ->whereNotin('id',function ($q){
                            $q->select('main_id')->from('trans')->whereBetween('ksm_date',[$this->Date1,$this->Date2]);
                        });

                return  $main;
            })
            ->columns([
                TextColumn::make('id')
                    ->label('رقم العقد'),
                TextColumn::make('Customer.CusName')
                    ->label('الاسم'),
                TextColumn::make('Bank.BankName')
                    ->label('المصرف')
                    ->visible(fn (Get $get): bool =>$this->By ==2),
                TextColumn::make('sul')
                    ->label('اجمالي العقد'),
                TextColumn::make('pay')
                    ->label('المسدد'),
                TextColumn::make('raseed')
                    ->label('الرصيد'),
                TextColumn::make('kst')
                    ->label('القسط'),
                TextColumn::make('LastKsm')
                    ->label('تاريخ أخر خصم'),
            ]);
    }

    public function mount($Date1,$Date2,$bank_id){
        $this->Date1=$Date1;
        $this->Date2=$Date2;
        $this->bank_id=$bank_id;

    }

    public function render()
    {
        return view('livewire.reports.rep-aksat-not-get');
    }
}
