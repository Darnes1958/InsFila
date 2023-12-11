<?php

namespace App\Livewire\Reports;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Livewire\Component;
use App\Livewire\Traits\MainTrait;
use App\Models\Bank;
use App\Models\Main;
use App\Models\Taj;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Group;

class RepStop extends Component implements HasTable, HasForms,HasActions
{
  public $bank_id;
  public $bank;
  public $taj;
  public $By=1;
  public $is_show=false;
  public $field='id';
  public $query;
  public $rep_name='All';
  public $Date1;
  public $Date2;
  public $Baky=5;
  public $BakyLabel='الباقي';

  use InteractsWithTable,InteractsWithForms,InteractsWithActions;

  public function printAction(): Action
  {
      return Action::make('print')
        ->label('طباعة')
        ->button()
        ->color('danger')
        ->icon('heroicon-m-printer')
        ->color('info')
        ->url(fn (): string => route('pdfstopall', ['bank_id'=>$this->bank_id,'Date1'=>$this->Date1,'Date2'=>$this->Date2,'By'=>$this->By]));
  }

  public function form(Form $form): Form
  {
    return $form

      ->schema([
        Group::make([
          Radio::make('By')
            ->label(' ')
            ->inlineLabel()
            ->inline()
            ->reactive()
            ->options([
              '2' => 'بالتجميعي',
              '1' => 'بفروع المصارف',

            ]),

        ]),
        Group::make([
        Select::make('bank')
          ->columnSpan(2)
          ->inlineLabel()
          ->options(Bank::all()->pluck('BankName', 'id')->toArray())
          ->searchable()
          ->reactive()
          ->Label('فرع المصرف')
          ->visible(function () {
            return $this->By==1;
          })
          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('bank');
            $this->field='id';
          }),
        Select::make('taj')
          ->columnSpan(2)
          ->inlineLabel()
          ->options(Taj::all()->pluck('TajName', 'id')->toArray())
          ->searchable()
          ->Label('المصرف التجميعي')
          ->reactive()
          ->visible(function () {
            return $this->By==2;
          })

          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('taj');
            $this->field='taj_id';
          }),
        DatePicker::make('Date1')
          ->inlineLabel()
          ->label('من')
          ->reactive(),
        DatePicker::make('Date2')
          ->inlineLabel()
          ->label('إلي')
          ->reactive(),
      ])->columns(5)
    ]);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Main $main)  {
        if ($this->By==1) {
          $main=Main::where('bank_id',$this->bank_id)
            ->has('Stop')

          ;
        }
        if ($this->By==2) {
          $main=Main::whereIn('bank_id',function ($q){
            $q->select('id')->from('banks')->where('taj_id',$this->bank_id);
          })
            ->has('Stop')
          ;
        }
        return  $main;
      })
      ->columns([
        TextColumn::make('id')
          ->label('رقم العقد'),
        TextColumn::make('acc')
          ->label('رقم الحساب'),
        TextColumn::make('Customer.CusName')
          ->label('الاسم'),
        TextColumn::make('sul')
          ->label('اجمالي العقد'),
        TextColumn::make('kst')
          ->label('القسط'),
        TextColumn::make('pay')
          ->label('المسدد'),
        TextColumn::make('raseed')
          ->label('الرصيد'),

        TextColumn::make('Stop.stop_date')
          ->label('تاريخ الإيقاف')
          ->color('info'),

      ])
      ->actions([
        \Filament\Tables\Actions\Action::make('print')
          ->hiddenLabel()
          ->button()
          ->color('danger')
          ->icon('heroicon-m-printer')

          ->color('info')
          ->url(fn (Main $record): string => route('pdfstopone', $record))


      ])
      ;


  }

  public function mount(){
    $date1=Carbon::now();
    $this->Date1=$date1->startOfYear()->toDateString();

    $this->Date2=date('Y-m-d');

  }

  public function render()
    {
        return view('livewire.reports.rep-stop');
    }
}
