<?php

namespace App\Livewire\Reports;

use App\Models\Bank;
use App\Models\Main;
use App\Models\Taj;
use App\Models\Wrongkst;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class RepTajSum extends Component implements HasTable, HasForms
{
    use InteractsWithTable,InteractsWithForms;


    public function table(Table $table):Table
    {
        return $table
            ->query(function (Taj $taj)  {

                Bank::all();


                return  $taj;
            })
            ->columns([
                TextColumn::make('id')
                    ->label('رقم المصرف'),
                TextColumn::make('TajName')
                    ->label('الاسم'),
                TextColumn::make('main_count')
                    ->counts('Main')
                    ->label('عدد العقود')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Main::count();})

                  ),
                TextColumn::make('main_sum_sul')
                    ->sum('Main','sul')
                    ->label('اجمالي العقود')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Main::sum('sul');})
                      ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                      )

                  ),
                TextColumn::make('main_sum_pay')
                    ->sum('Main','pay')
                    ->label('المسدد')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Main::sum('pay');})
                      ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                      )

                  ),


                TextColumn::make('main_sum_over_kst')
                    ->sum('Main','over_kst')
                    ->label('الفائض')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Main::sum('over_kst');})
                      ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                      )

                  ),
                TextColumn::make('main_sum_tar_kst')
                    ->sum('Main','tar_kst')
                    ->label('الترجيع')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Main::sum('tar_kst');})

                      ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                      )

                  ),
                TextColumn::make('wrong_kst_sum_kst')
                    ->sum('WrongKst','kst')
                    ->label('بالخطأ')
                  ->summarize(
                    Summarizer::make()
                      ->using(function (){return Wrongkst::sum('kst');})
                      ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                      )
                  ),
            ])

          ;
    }
    public function render()
    {
        return view('livewire.reports.rep-taj-sum');
    }
}
