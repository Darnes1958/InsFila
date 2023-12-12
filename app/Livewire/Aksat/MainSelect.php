<?php

namespace App\Livewire\Aksat;

use App\Models\Main;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class MainSelect extends Component implements HasForms
{
     use InteractsWithForms;
     public $the_main_id;

     public function updatedThe_main_id(){
         info('no');
     }

     public function form(Form $form): Form
     {
         return $form
             ->schema([
                 Select::make('the_main_id')
                     ->options(Main::all()->pluck('Customer.CusName', 'id')->toArray())
                     ->searchable()
                     ->reactive()
                     ->label('رقم العقد')
                     ->afterStateUpdated(function (callable  $get) {
                         info($get('the_main_id'));
                         $this->dispatch('TakeMainId',$get('the_main_id'));
                     })
                    ->dispatchEvent('TakeMainId',$this->the_main_id)
             ])->columns(4);
     }
    public function render()
    {
        return view('livewire.aksat.main-select');
    }
}
