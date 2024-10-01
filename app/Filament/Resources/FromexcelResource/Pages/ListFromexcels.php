<?php

namespace App\Filament\Resources\FromexcelResource\Pages;

use App\Filament\Resources\FromexcelResource;
use App\Imports\FromExcelImport;


use App\Livewire\Traits\AksatTrait;
use App\Models\Dateofexcel;
use App\Models\Fromexcel;
use App\Models\Main;
use App\Models\Taj;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListFromexcels extends ListRecords
{
    use AksatTrait;
    protected static string $resource = FromexcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Do')
                ->color('success')
                ->form([
                    Select::make('taj')
                        ->label('المصرف التجميعي')
                        ->options(Taj::all()->pluck('TajName','id'))
                        ->searchable()
                        ->preload()
                        ->default(2)
                        ->required(),
                    TextInput::make('headerrow')
                        ->default(10)
                        ->label('رقم سطر العنوان')
                        ->required(),
                ])
                ->action(function (array $data){
                    Fromexcel::truncate();
                    User::find(Auth::id())->update(['headerrow'=>$data['headerrow'],'taj'=>$data['taj']]);

                }),

            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->color('danger')
                ->use(FromExcelImport::class),
            Actions\Action::make('check')
                ->action(function (array $data){
                    $beginDate=Fromexcel::min('ksm_date');
                    $endDate=Fromexcel::max('ksm_date');
                    $res=Dateofexcel::where('taj_id',Auth::user()->taj)
                        ->whereBetween('date_begin',[$beginDate,$endDate])->first();
                    if ($res){
                        Fromexcel::truncate();
                        Notification::make()
                            ->title('يوجد تداخل في تاريخ الحافظة مع حافظة سابقة لنفس المصرف ')
                            ->send();
                        return false;

                    }

                    Dateofexcel::create([
                            'taj_id'=>Auth::user()->taj,
                            'date_begin'=>Fromexcel::min('ksm_date'),
                            'date_end'=>Fromexcel::max('ksm_date'),
                        ]
                    );
                }),
            Actions\Action::make('link')
             ->label('ربط بالعقود')
            ->action(function (){
                $fromexcel=Fromexcel::query()->get();

                foreach ($fromexcel as $item){
                    $main=Main::where('taj_id',$item->taj_id)->where('acc',$item->acc)->first();
                    if ($main){
                        $item->main_id=$main->id;
                        $item->save();
                        $this->Fill_From_Excel($main->id,$item->ksm,$item->ksm_date);
                    } else $this->StoreWrong($item->taj_id,$item->acc,$item->ksm_date,$item->ksm);
                }
            })
        ];
    }
}
