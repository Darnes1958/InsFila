<?php

namespace App\Livewire\Reports;

use App\Models\Tran;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;


use Livewire\Component;


use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;


use Filament\Tables\Table;


class TransRep extends Component implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;
  public $main_id=1;
  public function table(Table $table):Table
  {
    return $table
      ->query(function (Tran $tran)  {
        $tran=Tran::where('main_id','=',14);

        return  $tran;    })

      ->columns([
        TextColumn::make('kst_date')
          ->label('تاريخ القسط'),
        TextColumn::make('ksm_date')
          ->label('تاريخ الخصم'),
        TextColumn::make('ksm')
          ->label('الخصم'),
      ])
;

  }

  public function render()
    {
        return view('livewire.reports.trans-rep');
    }
}
