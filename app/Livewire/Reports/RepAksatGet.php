<?php

namespace App\Livewire\Reports;

use App\Models\Bank;
use App\Models\Taj;
use App\Models\Tran;
use Carbon\Traits\Date;
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

    protected $listeners = ['TakeBank'];

    public function TakeBank($bank_id){
        $this->bank_id=$bank_id;
        info('yes I have bank id');
    }
    public function TakeDate1($date1){
        $this->Date1=$date1;
    }
    public function TakeDate2($date2){
        $this->Date2=$date2;
    }

    public function table(Table $table):Table
    {
        return $table


            ->query(function (Tran $tran)  {
                Tran::wherein('main_id',function ($q){
                    $q->select('id')->from('mains')->where('bank_id',$this->bank_id);
                })
               ->where('ksm_date','>',$this->Date1)

               ->get();
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

    public function mount(string $Date1,string $Date2,string $bank_id){

        $this->Date1=$Date1;
        $this->Date2=$Date2;
        $this->bank_id=$bank_id;

    }
    public function render()
    {
        return view('livewire.reports.rep-aksat-get');
    }
}
