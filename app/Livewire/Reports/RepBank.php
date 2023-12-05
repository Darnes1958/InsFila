<?php

namespace App\Livewire\Reports;

use App\Livewire\Traits\MainTrait;
use App\Models\Bank;
use App\Models\Taj;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class RepBank extends Component implements HasTable, HasForms
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
                DatePicker::make('Date1')
                    ->label('من'),
                DatePicker::make('Date2')
                    ->label('إلي'),
            ])->columns(6);
    }



    public function table(Table $table):Table
    {

        return $table

            ->query(function (Bank $bank)  {
                if ($this->By==1) {
                    Bank::where('id','!=',null)->get();

                }
                if ($this->By==2) {$bank=Taj::all();}
                return  $bank;
            })
            ->columns([
                TextColumn::make('id')
                    ->label('رقم المصرف'),
                TextColumn::make('BankName')
                    ->label('الاسم'),

                TextColumn::make('main_count')->counts('Main')
                    ->label('عدد العقود'),
                TextColumn::make('main_sum_sul')
                    ->sum('Main','sul')
                    ->summarize(Sum::make() )

                    ->label('اجمالي العقود'),
                TextColumn::make('main_sum_pay')->sum('Main','pay')
                    ->label('المسدد'),
                TextColumn::make('main_sum_over_kst')->sum('Main','over_kst')
                    ->label('الفائض'),
                TextColumn::make('main_sum_tar_kst')->sum('Main','tar_kst')
                    ->label('الترجيع'),
                TextColumn::make('wrong_kst_sum_kst')->sum('WrongKst','kst')
                    ->label('بالخطأ'),



            ]);
    }

    public function render()
    {
        return view('livewire.reports.rep-bank');
    }
}
