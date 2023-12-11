<?php

namespace App\Livewire\Aksat;

use App\Livewire\Forms\StopForm;
use App\Models\Main;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Livewire\Component;
use App\Livewire\Forms\TarForm;
use App\Livewire\Traits\AksatTrait;
use App\Models\Overkst;
use App\Models\Tarkst;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class InpStop extends Component implements HasForms,HasTable,HasActions
{
  use InteractsWithForms,InteractsWithTable,InteractsWithActions;
  public $stop_date;
  public StopForm $stopForm;


  public function form(Form $form): Form
  {
     return $form
       ->schema([
         DatePicker::make('stop_date')
          ->label('تاريخ الإيقاف')
          ->required()
          ->inlineLabel()
       ])->columns(4);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Main $main)  {
        $main=Main::where('raseed','<=',0)
          ->whereNotIn('id',function ($q) {
            $q->select('main_id')->from('Stops');
          });
        return  $main;
      })
      ->columns([
        TextColumn::make('id')
         ->label('رقم العقد')
         ->sortable(),
        TextColumn::make('Customer.CusName')->sortable()->searchable()
          ->label('الاسم')
          ->sortable()
          ->searchable(),
        TextColumn::make('acc')->sortable()->searchable()
          ->label('رقم الحساب'),
        TextColumn::make('raseed')
          ->label('الرصيد'),
      ])

      ->bulkActions([

        BulkAction::make('إيقاف')
          ->color('danger')
          ->requiresConfirmation()
          ->action(function (Collection $records) {

              foreach ($records as  $item)
                $this->stopForm->Save($item->id,$this->stop_date);
          }),
      ])
      ->striped();
  }

   public function mount(){
    $this->stop_date=date('Y-m-d');
   }
    public function render()
    {
        return view('livewire.aksat.inp-stop');
    }
}
