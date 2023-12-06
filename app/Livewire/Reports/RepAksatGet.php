<?php

namespace App\Livewire\Reports;

use App\Models\Bank;
use App\Models\Taj;
use App\Models\Tran;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class RepAksatGet extends Component implements HasTable, HasForms
{

    use InteractsWithTable,InteractsWithForms;

public $bank_id;
public $Date1;
public $Date2;
    public function table(Table $table):Table
    {
        return $table
            ->query(function (Tran $tran)  {
                Tran::wherein('main_id',function ($q){
                    $q->select('id')->from('mains')->where('bank_id',$this->bank_id);
                })
                ->whereBetween('ksm_date',[$this->Date1,$this->Date2])->get();
                return  $tran;
            })
            ->columns([
                TextColumn::make('main_id')
                    ->label('رقم العقد'),
                TextColumn::make('Main.Customer.CusName')
                    ->label('الاسم'),
                TextColumn::make('Main.sul')
                    ->label('اجمالي العقود'),
                TextColumn::make('Main.pay')
                    ->label('المسدد'),
                TextColumn::make('ksm_date')
                    ->label('الفائض'),
                TextColumn::make('ksm')
            ]);
    }
    public function mount(string $Date1,string $Date2,string $bank_id){
        if ($bank_id==null) $bank_id=1;
        if ($Date1==null) $Date1=date('Y-m-d');
        if ($Date2==null) $Date2=date('Y-m-d');

        $this->Date1=$Date1;
        $this->Date2=$Date2;
        $this->bank_id=$bank_id;
    }
    public function render()
    {
        return view('livewire.reports.rep-aksat-get');
    }
}
