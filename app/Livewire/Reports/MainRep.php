<?php

namespace App\Livewire\Reports;

use App\Models\Main;

use App\Services\MainForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;


use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Component;

use Filament\Tables\Table;
use Filament\Forms\Form;

class MainRep extends Component implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;


    public function table(Table $table):Table
    {
      return $table
      ->query(function (Main $main)  {
       $main=Main::where('bank_id','!=',null);
      return  $main;    })


        ->columns([
          TextColumn::make('Customer.CusName')
            ->label('الاسم'),
          TextColumn::make('Bank.BankName')
            ->label('المصرف'),

          TextColumn::make('Bank.Taj.TajName')
            ->label('المصرف التجميعي'),

          TextColumn::make('sul')
            ->label('اجمالي العقد'),
          TextColumn::make('kst')
            ->label('القسط'),
          TextColumn::make('pay')
            ->label('المدفوع'),


        ])
        ->actions([
        ])
        ->headerActions([
        ]);

    }



    public function render()
    {
        return view('livewire.reports.main-rep');
    }
}
