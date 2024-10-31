<?php

namespace App\Filament\Resources\FromexcelResource\Pages;

use App\Filament\Resources\FromexcelResource;
use App\Imports\FromExcelImport;


use App\Livewire\Traits\AksatTrait;
use App\Models\Dateofexcel;
use App\Models\Fromexcel;
use App\Models\Hafitha;
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
                $fromexcel=Fromexcel::query()->where('haf_id',null)->get();
                if ($fromexcel->count()>0){
                  $haf=Hafitha::create([
                      'from_date'=>$fromexcel->min('ksm_date'),
                      'to_date'=>$fromexcel->max('ksm_date'),
                      ]);
                } else return;

                foreach ($fromexcel as $item){
                    $main=Main::where('taj_id',$item->taj_id)->where('acc',$item->acc)->first();

                    if ($main){
                        $type=$this->Fill_From_Excel($main->id,$item->ksm,$item->ksm_date,$haf->id,$item->id);
                        $item->main_id=$main->id;
                        $item->main_name=$main->Customer->name;
                        $item->kst_type=$type;
                        $item->save();
                    } else {
                        $this->StoreWrong($item->taj_id,$item->acc,$item->name,$item->ksm_date,$item->ksm,$haf->id);
                        $item->kst_type='wrong';
                        $item->save();
                    }
                }

                Fromexcel::where('haf_id',null)->update(['haf_id'=>$haf->id]);

                $haf->tot=Fromexcel::where('haf_id',$haf->id)->sum('ksm');
                $haf->morahel=Fromexcel::where('haf_id',$haf->id)->where('kst_type','normal')->sum('ksm');
                $haf->over_kst=Fromexcel::where('haf_id',$haf->id)->where('kst_type','over')->sum('ksm');
                $haf->over_kst_arc=Fromexcel::where('haf_id',$haf->id)->where('kst_type','over_arc')->sum('ksm');
                $haf->wrong_kst=Fromexcel::where('haf_id',$haf->id)->where('kst_type','wrong')->sum('ksm');
                $haf->save();
            })
        ];
    }
}
