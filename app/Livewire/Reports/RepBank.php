<?php

namespace App\Livewire\Reports;


use App\Models\Bank;

use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tarkst;
use App\Models\Wrongkst;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;


use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use Livewire\Component;


class RepBank extends Component implements HasTable, HasForms
{

    use InteractsWithTable,InteractsWithForms;

    public $By=1;
  public $sul;
  public $pay;
  public $raseed;
  public $count;
  public $over;
  public $tar;
  public $wrong;
  public array $data_list= [
    'calc_columns' => [
      'main_count',
      'main_sum_sul',
      'main_sum_pay',
      'main_sum_raseed',
      'main_sum_over_kst',
      'main_sum_tar_kst',
      'wrong_kst',
      'BankName'
    ],
  ];
    public function table(Table $table):Table
    {
        return $table
            ->query(function (Bank $bank)  {
               $bank=  Bank::has('main');

              $this->sul=number_format(Main::sum('sul'),0, '', ',')  ;
              $this->pay=number_format(Main::sum('pay'),0, '', ',')  ;
              $this->raseed=number_format(Main::sum('raseed'),0, '', ',')  ;
              $this->count=number_format(Main::count(),0, '', ',')  ;
              $this->over=number_format(Overkst::sum('kst'),0, '', ',')  ;
              $this->tar=number_format(Tarkst::sum('kst'),0, '', ',')  ;
              $this->wrong=number_format(Wrongkst::where('status','غير مرجع')->sum('kst'),0, '', ',')  ;
                return  $bank;
            })
            ->columns([
                TextColumn::make('id')
                    ->label('رقم المصرف'),
                TextColumn::make('BankName')
                    ->label('الاسم'),
                TextColumn::make('main_count')
                    ->counts('Main')
                    ->label('عدد العقود')
                ,
                TextColumn::make('main_sum_sul')
                    ->sum('Main','sul')
                    ->label('اجمالي العقود')
                ,
                TextColumn::make('main_sum_pay')
                    ->sum('Main','pay')
                    ->label('المسدد')
                ,
                TextColumn::make('main_sum_raseed')
                    ->sum('Main','raseed')
                    ->label('الرصيد')
                ,

                TextColumn::make('main_sum_over_kst')
                    ->sum('Main','over_kst')
                    ->label('الفائض')
                ,
                TextColumn::make('main_sum_tar_kst')
                    ->sum('Main','tar_kst')
                    ->label('الترجيع')
                ,
                TextColumn::make('wrong_kst')
                    ->state(function (Bank $record){
                        return Wrongkst::where('taj_id',$record->taj_id)->where('status','غير مرجع')->sum('kst');
                    })

                    ->label('بالخطأ'),
            ])
          ->contentFooter(view('sum-footer',$this->data_list))
           ;
    }

    public function render()
    {
        return view('livewire.reports.rep-bank');
    }
}
