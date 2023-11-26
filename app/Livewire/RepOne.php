<?php

namespace App\Livewire;

use App\Models\Bank;
use App\Models\Main;
use App\Services\MainForm;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Livewire\Component;
use function Symfony\Component\String\b;

class RepOne extends Component implements HasTable, HasForms
{
public Bank $bank;
public $bank_id;

    use InteractsWithTable,InteractsWithForms;


    protected function getFormSchema(): array
    {
        return [
            Select::make('bank_id')

              ->options(Bank::all()->pluck('BankName', 'id')->toArray())
              ->searchable()
              ->afterStateUpdated(function (callable $get) {

                  $this->bank_id=$get('bank_id');
                  $this->table($this->table);
              })
              ->reactive(),



        ];
    }




    public function render()
    {
        return view('livewire.rep-one');
    }



    public function table(Table $table):Table
    {

      return $table
        ->query(Main::where('bank_id',$this->bank_id))
        ->columns([
            TextColumn::make('Customer.CusName'),
            TextColumn::make('Bank.BankName'),
            TextColumn::make('Bank.Taj.TajName'),

            TextColumn::make('sul')

        ])

          ->actions([
              EditAction::make()
                  ->slideOver()
                  ->model(Main::class)
                  ->form(MainForm::schema())
          ])
          ->headerActions([
              CreateAction::make()
                  ->slideOver()
                  ->model(Main::class)
                  ->form(MainForm::schema())
          ]);


    }
}
