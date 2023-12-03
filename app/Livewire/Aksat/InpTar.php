<?php

namespace App\Livewire\Aksat;


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
use Livewire\Component;

class InpTar extends Component implements HasForms,HasTable,HasActions

{
    use InteractsWithForms,InteractsWithTable,InteractsWithActions;
    use AksatTrait;

    public TarForm $tarForm;

    public $tar_what='=';
    public $tar_label='ترجيع';
    public $tar_color='info';

    public function deleteAction(): Action
    {
        if ($this->tar_what=='=')
        return Action::make('delete')
            ->label('الغاء ترجيع')
            ->badge()
            ->color('danger')
            ->icon('heroicon-m-trash')

            ->color('danger')

            ->action(fn () => [$this->tar_what='!=',$this->tar_label='الغاء الترجيع',$this->tar_color='danger']);
        else
            return Action::make('delete')
                ->label('ترجيع')
                ->badge()
                ->color('success')
                ->icon('heroicon-m-plus')

                ->color('success')
                ->action(fn () => [$this->tar_what='=',$this->tar_label='الترجيع',$this->tar_color='info']);


    }

    public function table(Table $table):Table
    {
        return $table
            ->query(function (Overkst $tran)  {
                $tran=Overkst::where('tar_id',$this->tar_what,0);
                return  $tran;
            })
            ->columns([
                TextColumn::make('Main.Customer.CusName')->sortable()->searchable()
                    ->label('الاسم'),
                TextColumn::make('main.acc')->sortable()->searchable()
                    ->label('رقم الحساب'),
                TextColumn::make('over_date')
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('القسط'),
            ])

            ->bulkActions([

                    BulkAction::make($this->tar_label)
                        ->color($this->tar_color)
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            if ($this->tar_what=='=')
                              foreach ($records as  $item){
                                  $this->tarForm->reset();
                                  $this->tarForm->SetTarFromOver($item->id);
                                  $res=Tarkst::create( $this->tarForm->all() );
                                  Overkst::where('id',$item->id)->update(['tar_id'=>$res->id,'status'=>'مرجع']);
                                  $this->TarTarseed($item->main_id);
                              }
                            else foreach ($records as  $item){
                                Tarkst::where('id',$item->tar_id)->delete();
                                Overkst::where('id',$item->id)->update(['tar_id'=>0,'status'=>'غير مرجع']);
                                $this->TarTarseed($item->main_id);
                            }
                            }),
                ])


            ->striped();
    }

    public function render()
    {
        return view('livewire.aksat.inp-tar');
    }
}
