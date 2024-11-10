<?php

namespace App\Filament\Pages;

use App\Enums\KsmType;
use App\Models\Main;
use App\Models\Tran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KsmKst extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.ksm-kst';
    protected ?string $heading='';
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->id()==1;
  }

    public $contData;
    public $main_id;
    public $acc;
    public $ksm_date;
    public $ksm;
    public $ksm_type_id;
    public $main;
    public $accTaken=false;


    public function mount(): void
    {
        $this->ksm_type_id=KsmType::المصرف;
        $this->ksm_date=now();
        $this->fillcontForm();
    }

    public function fillcontForm(){
        $this->contForm->fill(['ksm_type_id'=>$this->ksm_type_id,'main_id'=>$this->main_id,'acc'=>$this->acc,
            'ksm_date'=>$this->ksm_date,'ksm'=>$this->ksm,]);

    }
    public function go($who){
        $this->dispatch('gotoitem', test: $who);
    }
    public function chkacc(Set $set)
    {

            $this->main=Main::where('acc',$this->acc)->first();
            if ($this->main) {
                $this->main_id=$this->main->id;
                $this->ksm=$this->main->kst;
                $this->accTaken=true;
                $this->go('ksm_date');
            } else {
                $this->accTaken=false;
                $this->main_id=null;
            }



        $this->fillcontForm();
    }
    protected function getForms(): array
    {
        return array_merge(parent::getForms(),[
            'contForm'=> $this->makeForm()
                ->model(Tran::class)
                ->schema($this->getContFormSchema())
                ->statePath('contData'),
        ]);
    }

    public function store(){

        Notification::make()
            ->title('تم تحزين البانات بنجاح')
            ->success()
            ->send();
        $this->mount();

    }
    protected function getContFormSchema(): array
    {
        return [
            Section::make()
             ->schema([
                 Radio::make('ksm_type_id')
                 ->options(KsmType::class)
                 ->live()
                 ->afterStateUpdated(function ($state){
                     $this->ksm_type_id=$state;
                 })
                 ->inline()
                 ->inlineLabel()
                 ->hiddenLabel()
                 ->columnSpanFull()
                 ->required(),
            TextInput::make('acc')->label('رقم الحساب')
                     ->live(debounce:400)
                     ->columnSpan(3)
                     ->autocomplete('off')
                     ->datalist(function (?string $state , TextInput $component,?Model $record ,
                                          $modelsearch='\App\Models\Main' , $fieldsearch='acc') {
                         $options =[];
                         if($state!=null  and Str::length($state)>=3){
                             $options= $modelsearch::whereRaw($fieldsearch.
                                 ' like \'%'.$state.'%\'')
                                 ->limit(20)
                                 ->pluck('acc')
                                 ->toarray();
                         }
                         return $options;
                     })
                ->afterStateUpdated(function ($state){
                    $this->acc=$state;
                })
                ->extraAttributes([
                    'wire:keydown.enter'=>'chkacc',

                ])

                 ->id('acc'),

                 Select::make('main_id')
                  ->columnSpan(3)
                  ->live()
                     ->relationship('Main','name',modifyQueryUsing: fn ($query) =>
                     $query->when($this->accTaken,function ($q){
                         $q->where('acc',$this->acc);
                     }),)
                     ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} {$record->Customer->name} {$record->sul}")

                  ->required()
                  ->searchable()
                  ->preload()
                  ->id('main_id')
                  ->label('رقم العقد'),
                 DatePicker::make('ksm_date')
                  ->label('التاريخ')
                     ->live()
                     ->afterStateUpdated(function ($state){
                         $this->ksm_date=$state;
                     })

                     ->columnSpan(3)
                  ->required()
                     ->extraAttributes([
                         'wire:keydown.enter'=>'$dispatch("gotoitem", {test: "ksm"})'

                     ])
                 ->id('ksm_date'),
                 TextInput::make('ksm')
                 ->label('المبلغ')
                 ->columnSpan(3)
                  ->numeric()
                  ->required()
                 ->id('ksm')


             ])
             ->columns(6)

        ];
    }
}
