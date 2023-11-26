<?php

namespace App\Livewire;

use App\Models\Bank;
use App\Models\Main;
use App\Models\Taj;
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

use Livewire\Component;
use Filament\Forms\Form;


class RepOne extends Component implements HasTable, HasForms
{

public $bank_id;
public $bank;
public $taj;
public $By=1;
public $is_show=false;
public $field='id';

    use InteractsWithTable,InteractsWithForms;

    public function updatedBy(){
      $this->form($this->form);
    }

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('bank')
          ->options(Bank::all()->pluck('BankName', 'id')->toArray())
          ->searchable()
          ->reactive()
          ->hiddenLabel()
          ->visible($this->By==1)
          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('bank');
            $this->field='id';
            $this->table($this->table);

          }),
        Select::make('taj')
          ->options(Taj::all()->pluck('TajName', 'id')->toArray())
          ->searchable()
          ->hiddenLabel()
          ->reactive()
          ->visible($this->By==2)
          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('taj');
            $this->field='taj_id';
            $this->table($this->table);
          })
      ]);
  }

    public function render()
    {
        return view('livewire.rep-one');
    }

    public function table(Table $table):Table
    {
      return $table
        ->query(Bank::where($this->field,$this->bank_id))
        ->columns([
            TextColumn::make('Main.Customer.CusName')
             ->label('الاسم'),
            TextColumn::make('BankName')
             ->label('المصرف')
             ->visible($this->field=='taj_id'),
            TextColumn::make('Taj.TajName')
             ->label('المصرف التجميعي')
             ->visible($this->field=='id'),
            TextColumn::make('Main.sul')
              ->label('اجمالي العقد'),
            TextColumn::make('Main.kst')
              ->label('القسط'),
            TextColumn::make('Main.pay')
              ->label('المدفوع'),


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
