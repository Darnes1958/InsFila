<?php

namespace App\Livewire\Reports;

use App\Livewire\Traits\MainTrait;
use App\Models\Bank;
use App\Models\Main;
use App\Models\Taj;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;


use Filament\Forms;

use Livewire\Component;
use Filament\Forms\Form;



class RepAll extends Component implements HasTable, HasForms
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

    use InteractsWithTable,InteractsWithForms;
    use MainTrait;
    public function updatedBy(){

      $this->form($this->form);

    }

  public function form(Form $form): Form
  {
    return $form
      ->schema([

        Select::make('bank')
            ->columnSpan(2)
          ->options(Bank::all()->pluck('BankName', 'id')->toArray())

          ->searchable()
          ->reactive()
          ->Label('فرع المصرف')
          ->visible($this->By==1)
          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('bank');
            $this->field='id';
            $this->table($this->table);

          }),
        Select::make('taj')
            ->columnSpan(2)
          ->options(Taj::all()->pluck('TajName', 'id')->toArray())
          ->searchable()
          ->Label('المصرف التجميعي')
          ->reactive()
          ->visible($this->By==2)
          ->afterStateUpdated(function (callable $get) {
            $this->bank_id=$get('taj');
            $this->field='taj_id';
            $this->table($this->table);
          }),
        Select::make('rep_name')
           ->columnSpan(2)
          ->label('النقرير')
          ->default('All')
          ->reactive()

          ->options([
            'All' => 'كشف بالأسماء',
            'Mosdada' => 'المسددة',
            'Motakra' => 'المتأخرة',
            'Mohasla' => 'المحصلة',
            'Not_Mohasla' => 'الغير محصلة',
          ])
            ->afterStateUpdated(function (callable $get){
              if ($get('rep_name')=='Mosdada') {$this->Baky=5;$this->BakyLabel='الباقي';}
              if ($get('rep_name')=='Motakra') {$this->Baky=1;$this->BakyLabel='عدد الأقساط المتأخرة';}
            })  ,
          TextInput::make('Baky')
              ->label(function (){
                return $this->BakyLabel;
              })
              ->reactive()
          ->numeric()
              ->visible(fn (Forms\Get $get): bool => $get('rep_name')=='Mosdada' || $get('rep_name')=='Motakra'),

          DatePicker::make('Date1')
            ->label('من')
            ->visible(fn (Forms\Get $get): bool => $get('rep_name')=='Mohasla' || $get('rep_name')=='Not_Mohasla'),
          DatePicker::make('Date2')
            ->label('إلي')
              ->visible(fn (Forms\Get $get): bool => $get('rep_name')=='Mohasla' || $get('rep_name')=='Not_Mohasla'),

      ])->columns(7);
  }



    public function table(Table $table):Table
    {
      return $table
        ->query(function (Main $main)  {
            if ($this->By==1) {
                 $main=Main::where('bank_id',$this->bank_id)
                 ->when($this->rep_name=='Mosdada' , function ($q) {
                     $q->where('raseed','<=',$this->Baky); })
                 ->when($this->rep_name=='Motakra' , function ($q) {
                    $q->where('late','>=',$this->Baky); })

                 ;
            }
            if ($this->By==2) {
                $main=Main::whereIn('bank_id',function ($q){
                    $q->select('id')->from('banks')->where('taj_id',$this->bank_id);
                    })
                    ->when($this->rep_name=='Motakra' , function ($q) {
                        $q->where('late','>=',$this->Baky); })
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
            TextColumn::make('Late')
                ->label('متأخرة')
                ->visible(fn (Forms\Get $get): bool =>$this->rep_name =='Motakra')
                ->color('danger'),

            TextColumn::make('LastKsm')
                ->label('ت.أخر قسط')
                ->visible(fn (Forms\Get $get): bool =>$this->rep_name =='Motakra')
                ->color('danger'),


        ])

          ;


    }

    public function mount(){

     $this->LateChk();
    }
    public function render()
    {
        return view('livewire.reports.rep-all');
    }
}
