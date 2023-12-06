<?php

namespace App\Livewire\Reports;

use App\Models\Bank;
use App\Models\Taj;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
                    ->label('عدد العقود'),
                TextColumn::make('main_sum_sul')
                    ->sum('Main','sul')
                    ->label('اجمالي العقود'),
                TextColumn::make('main_sum_pay')
                    ->sum('Main','pay')
                    ->label('المسدد'),


                TextColumn::make('main_sum_over_kst')
                    ->sum('Main','over_kst')
                    ->label('الفائض'),
                TextColumn::make('main_sum_tar_kst')
                    ->sum('Main','tar_kst')
                    ->label('الترجيع'),
                TextColumn::make('wrong_kst_sum_kst')
                    ->sum('WrongKst','kst')
                    ->label('بالخطأ'),
            ]);
    }
    public function render()
    {
        return view('livewire.reports.rep-taj-sum');
    }
}
