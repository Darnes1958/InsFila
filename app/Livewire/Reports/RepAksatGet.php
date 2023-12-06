<?php

namespace App\Livewire\Reports;

use App\Models\Tran;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class RepAksatGet extends Component implements HasTable, HasForms
{


    use InteractsWithTable,InteractsWithForms;
  #[Reactive]
public $bank_id;
  #[Reactive]
public $Date1;
  #[Reactive]
public $Date2;



    public function table(Table $table):Table
    {
        return $table
            ->query(function (Tran $tran)  {
               $tran= Tran::wherein('main_id',function ($q){
                    $q->select('id')->from('mains')->where('bank_id',$this->bank_id);
                })
               
               ->whereBetween('ksm_date',[$this->Date1,$this->Date2]);

               return  $tran;
            })
            ->columns([
                TextColumn::make('main_id')
                    ->label('رقم العقد'),
                TextColumn::make('Main.Customer.CusName')
                    ->label('الاسم'),
                TextColumn::make('Main.sul')
                    ->label('اجمالي العقد'),
                TextColumn::make('Main.pay')
                    ->label('المسدد'),
                TextColumn::make('ksm_date')
                    ->label('تاريخ الخصم'),
                TextColumn::make('ksm')
                    ->label('الخصم'),
            ]);
    }

    public function mount($Date1,$Date2,$bank_id){
     $this->Date1=$Date1;
     $this->Date2=$Date2;
     $this->bank_id=$bank_id;

    }

    public function render()
    {
        return view('livewire.reports.rep-aksat-get');
    }
}
