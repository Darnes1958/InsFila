<?php

namespace App\Filament\Pages;

use App\Enums\KsmType;
use App\Livewire\Traits\AksatTrait;
use App\Models\aksat\kst_trans;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\Operations;
use App\Models\Tran;
use App\Models\Trans_arc;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class KsmKst extends Page implements HasTable
{
    use InteractsWithTable;
    use AksatTrait;
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
    public $message;
    public $color='myRed';
    public $is_arc=false;
    public $has_baki=false;


    public function mount(): void
    {
        $this->is_arc=false;
        $this->message=null;
        $this->main_id=null;
        $this->accTaken=null;
        $this->ksm_type_id=KsmType::المصرف;
        $this->ksm_date=now();
        $this->fillcontForm();
        $this->go('acc');
    }

    public function fillcontForm(){
        if ($this->main_id){
            if (! $this->is_arc) $this->main=Main::find($this->main_id);
            else $this->main=Main_arc::find($this->main_id);
            $this->message=null;
            if ($this->main->raseed<=0) {$this->message='خصم قسط بالفائض';$this->color='myYellow';}
            if ($this->is_arc) {$this->message='خصم قسط بالفائض من الأرشـــــــيف';$this->color='myGreen';}

            $this->contForm->fill(['ksm_type_id'=>$this->ksm_type_id,'main_id'=>$this->main_id,'acc'=>$this->acc,
                'ksm_date'=>$this->ksm_date,'ksm'=>$this->ksm,'name'=>$this->main->Customer->name,'sul'=>$this->main->sul,
                'pay'=>$this->main->pay,'raseed'=>$this->main->raseed,'bank'=>$this->main->Taj->TajName  ]);
        }

        else
          $this->contForm->fill(['ksm_type_id'=>$this->ksm_type_id,'main_id'=>$this->main_id,'acc'=>$this->acc,
                'ksm_date'=>$this->ksm_date,'ksm'=>$this->ksm,]);

    }

    public function go($who){
        $this->dispatch('gotoitem', test: $who);
    }
    public function chkacc()
    {
        $this->message=null;
        $this->is_arc=false;
            $m=Main::where('acc',$this->acc)->get();
            if ($m->count()>0) {
                if ($m->count()==1) {
                    $this->main_id=$m[0]['id'];
                    $this->main=$m->first();
                    $this->ksm=$this->main->kst;
                } else {
                    $this->message='يوجد أكثر من عقد لهذا الحساب .. يجب اختيار رقم العقد من القائمة';
                    $this->ksm=null;
                    $this->main_id=null;
                }
                $this->accTaken=true;
                $this->go('ksm_date');
            } else {
                $m=Main_arc::where('acc',$this->acc)->first();
                if ($m){
                    $this->is_arc=true;
                    $this->main_id=$m->id;
                    $this->main=$m;
                    $this->ksm=$this->main->kst;
                    $this->go('ksm_date');
                } else
                {$this->accTaken=false;
                $this->main_id=null;}
            }
        $this->fillcontForm();
    }
    public function chkmainid()
    {
            $this->message=null;
            $this->is_arc=false;

            $this->main=Main::where('id',$this->main_id)->first();
            $this->acc=$this->main->acc;
            $this->ksm=$this->main->kst;
            $this->accTaken=true;
            $this->fillcontForm();
            $this->go('ksm_date');
    }
    public function chkmainArc()
    {
        $this->message=null;
        $this->is_arc=true;

        $this->main=Main_arc::where('id',$this->main_id)->first();
        $this->ksm=$this->main->kst;
        $this->accTaken=true;
        $this->fillcontForm();
        $this->go('ksm_date');
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
        if (!$this->is_arc) $this->validate(); else {if (!$this->ksm || $this->ksm<=0 || !$this->ksm_date) return; }
        if ($this->is_arc) self::StoreOver2($this->main,$this->ksm_date,$this->ksm,0);
        else {
             self::StoreKst($this->main_id,$this->ksm_date,$this->ksm,0,$this->ksm_type_id);
        }

        Notification::make()
            ->title('تم تحزين البانات بنجاح')
            ->success()
            ->send();

        $this->fillcontForm();
        $this->go('acc');

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
                    $this->main_id=null;
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

                  ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} {$record->Customer->name} {$record->sul} {$record->kst}")
                     ->afterStateUpdated(function ($state){
                       $this->main_id=$state;

                       $this->chkmainid();
                     })

                  ->searchable()
                  ->preload()
                  ->id('main_id')
                  ->label('رقم العقد'),

                 Placeholder::make('notes')
                  ->content(function (){
                      return new HtmlString('<span class="'.$this->color.' ">'.$this->message.'</span>');
                  })
                  ->hidden(fn(): bool=>$this->message==null)
                  ->live()
                  ->columnSpan('full')
                  ->hiddenLabel(),

                 Section::make()
                  ->schema([
                      TextInput::make('name')
                       ->readOnly()
                       ->columnSpan(3)
                       ->label(''),
                      TextInput::make('bank')
                          ->readOnly()
                          ->columnSpan(3)
                          ->label(''),
                      TextInput::make('sul')
                          ->readOnly()
                          ->columnSpan(2)
                          ->label(''),
                      TextInput::make('pay')
                          ->readOnly()
                          ->columnSpan(2)
                          ->label(''),
                      TextInput::make('raseed')
                          ->readOnly()
                          ->columnSpan(2)
                          ->label(''),



                  ])->columns(6)

                 ,
                 DatePicker::make('ksm_date')
                  ->label('التاريخ')
                  ->live()
                  ->afterStateUpdated(function ($state){
                         $this->ksm_date=$state;
                     })
                  ->columnSpan(3)
                  ->required()
                  ->validationMessages([
                         'required' => 'يجب ادخال التاريخ بشكل صحيح',
                     ])
                  ->extraAttributes(['wire:keydown.enter'=>'$dispatch("gotoitem", {test: "ksm"})'])
                  ->id('ksm_date'),
                 TextInput::make('ksm')
                  ->label('المبلغ')
                  ->columnSpan(3)
                  ->validationMessages([
                         'required' => 'يجب ادخال قيمة القسط',
                     ])
                  ->afterStateUpdated(function ($state){$this->ksm=$state;})
                  ->numeric()
                  ->required()
                  ->extraAttributes(['wire:keydown.enter'=>'store'])
                  ->id('ksm')
             ])
             ->columns(6)
        ];
    }

    public  function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('لا توجد أقساط مخصومة')
                ->emptyStateDescription('لم يتم خصم أقساط بعد')
                ->defaultPaginationPageOption(12)
                ->paginationPageOptions([5,12,15,50,'all'])
                ->defaultSort('ser')
                ->query(function (){
                    if (!$this->is_arc)
                     $tran=Tran::where('main_id',$this->main_id);
                    else
                     $tran=Trans_arc::where('main_id',$this->main_id);
                    $this->has_baki=$tran->sum('baky')>0;
                    return $tran;
                })
                ->columns([
                    TextColumn::make('ser')
                        ->size(TextColumnSize::ExtraSmall)
                        ->color('primary')
                        ->sortable()
                        ->label('ت'),
                    TextColumn::make('kst_date')
                        ->size(TextColumnSize::ExtraSmall)
                        ->toggleable()
                        ->toggledHiddenByDefault()
                        ->sortable()
                        ->label('ت.الاستحقاق'),
                    TextColumn::make('ksm_date')
                        ->size(TextColumnSize::ExtraSmall)
                        ->toggleable()
                        ->sortable()
                        ->label('ت.الخصم'),
                    TextColumn::make('ksm')
                        ->size(TextColumnSize::ExtraSmall)
                        ->label('الخصم'),
                    TextColumn::make('baky')
                        ->size(TextColumnSize::ExtraSmall)
                        ->visible(fn()=>$this->has_baki)
                        ->label('الباقي'),
                    TextColumn::make('ksm_type_id')
                        ->size(TextColumnSize::ExtraSmall)
                        ->toggleable()
                        ->toggledHiddenByDefault()
                        ->label('طريقة الدفع'),
                    TextColumn::make('ksm_notes')
                        ->toggleable()
                        ->size(TextColumnSize::ExtraSmall)
                        ->label('ملاحظات'),
                ])
                ->actions([
                    Action::make('del')
                        ->iconButton()
                        ->visible(fn($record)=> $record->baky==0 && !$this->is_arc)
                        ->icon('heroicon-o-trash')
                        ->iconSize(IconSize::Small)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Model $record){
                           $record->delete();
                           self::MainTarseed2($this->main_id);
                           self::SortTrans2($this->main_id);
                           $this->fillcontForm();
                        })




                ]);
    }

}
