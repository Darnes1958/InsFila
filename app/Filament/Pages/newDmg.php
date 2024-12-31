<?php

namespace App\Filament\Pages;

use App\Livewire\Forms\MainForm;
use App\Livewire\Forms\OverForm;
use App\Livewire\Forms\TransForm;
use App\Livewire\Traits\MainTrait;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\Sell;
use App\Models\Tran;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class newDmg extends Page implements HasInfolists,HasForms
{
    use InteractsWithInfolists,InteractsWithForms,MainTrait;
    protected ?string $heading='';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.new-dmg';


    public static function shouldRegisterNavigation(): bool
    {
        return  false;
    }


    public Main $mainRec;

    public $the_main_id;

    public $show=false;
    public MainForm $data;
    public TransForm $tranForm;
    public OverForm $overForm;
    public function mount(): void
    {
        $this->mainRec=Main::first();
    }
    public function create(): void
    {
        $this->tranForm->reset();
        $this->tranForm->FillTrans($this->the_main_id);
        $this->tranForm->ksm_date=$this->data->sul_begin;
        $this->tranForm->ksm=$this->mainRec->raseed;
        $this->tranForm->ksm_notes='قيمة تم ضمها للعقد رقم : '.$this->data->id ;
        $this->overForm->FillAny();
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $validator = $e->validator;
            info($validator->errors());
            throw $e;
        }
        if ($this->data->sul<$this->mainRec->raseed) {
            Notification::make()
                ->title('لا يجوز أن يكون إجمالي العقد الجديد أصغر من السابق')
                ->icon('heroicon-o-x-circle')
                ->body('يرجي مراجعة قيمة العقد السابق والحالي.')
                ->color('danger')
                ->danger()
                ->send();
            return;
        }
        DB::connection(Auth()->user()->company)->beginTransaction();
        try {
            $this->data->last_cont=$this->mainRec->id;
            Main::create($this->data->all());
            Tran::create($this->tranForm->all());
            $this->MainTarseed($this->the_main_id);

            $this->toArc($this->the_main_id,$this->data,$this->tranForm,$this->overForm);

            DB::connection(Auth()->user()->company)->commit();
            $this->show=false;
            Notification::make()
                ->title('تمت عملية ضم العقد بنجاح')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('حدث خطأ !!')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->danger()
                ->send();
            info($e);
            DB::connection(Auth()->user()->company)->rollback();
        }
    }
    public  function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Main::class)
            ->schema([
                \Filament\Forms\Components\Group::make([
                    Section::make()
                    ->schema([
                        Select::make('sell_id')
                            ->label('الفاتورة')
                            ->relationship('Sell','name',modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query) =>
                            $query->WhereDoesntHave('Main')->where('price_type_id','=',3),)
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} {$record->Customer->name} {$record->total}")
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state){
                                if (!$state) {$this->the_main_id=null;return;}
                                $sell=Sell::find($state);
                                $this->the_main_id=Main::where('customer_id',$sell->customer_id)->first()->id;
                                $this->mainRec=Main::find($this->the_main_id);

                                $this->data->id=Main::max('id')+1;
                                $this->data->customer_id=$this->mainRec->customer_id;
                                $this->data->acc=$this->mainRec->acc;
                                $this->data->bank_id=$this->mainRec->bank_id;
                                $this->data->sul_begin=date('Y-m-d');
                                $this->data->sul=$sell->baky;

                            })
                            ->required()
                            ->columnSpan(2),
                        Select::make('customer_id')
                            ->label('الزبون')
                            ->relationship('Customer','name')
                            ->disabled(),
                        Select::make('bank_id')
                            ->label('المصرف')
                            ->relationship('Bank','BankName')
                            ->disabled(),
                        TextInput::make('acc')
                            ->label('رقم الحساب')
                            ->disabled(),
                        \Filament\Forms\Components\Actions::make([
                            Action::make('store')
                             ->label('تخزين')
                            ->visible(function (){return $this->data->kst!=null;})
                            ->action(function (){
                                $this->create();
                            }),
                            ])
                    ])->columns(8)
                ]),
                \Filament\Forms\Components\Group::make([
                    Section::make(new HtmlString('<div class="text-danger-600">بيانات العقد الجديد</div>'))
                        ->schema([
                            TextInput::make('id')
                                ->label('رقم العقد')
                                ->required()
                                ->unique()
                                ->unique(table: Main_arc::class)
                                ->default(Main::max('id')+1)
                                ->autofocus()
                                ->numeric(),
                            DatePicker::make('sul_begin')
                                ->required()
                                ->label('تاريخ العقد')
                                ->maxDate(now())
                                ->default(now()),
                            TextInput::make('sul')
                                ->label('قيمة العقد')
                                ->disabled()
                                ->required(),
                            TextInput::make('kst_count')
                                ->label('عدد الأقساط')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get,Set $set) {
                                    if ($get('sul') && $get('kst_count')
                                        && (!$get('kst') ||  $get('kst')==' ')){
                                        $val=$get('sul') / $get('kst_count');
                                        $set('kst', $val);
                                    }
                                })
                                ->required(),
                            TextInput::make('kst')
                                ->label('القسط')
                                ->required(),

                            TextInput::make('notes')
                                ->label('ملاحظات')->columnSpanFull()
                        ])
                        ->columns(4)

                ])->visible(function (){
                    return $this->the_main_id !=null;
                })
            ]);
    }

    public function mainInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->mainRec)
            ->schema([
                Group::make([
                    \Filament\Infolists\Components\Section::make(new HtmlString('<div class="text-danger-600">بيانات العقد السابق</div>'))
                        ->schema([
                            TextEntry::make('sul')->label('قيمة العقد')->color('info'),
                            TextEntry::make('kst')->label('القسط'),
                            TextEntry::make('pay')->label('المدفوع'),
                            TextEntry::make('raseed')->label('المتبقي')->color('danger')->weight(FontWeight::ExtraBold),
                        ])
                        ->columns(4)
                        ->visible(function (){
                            return $this->the_main_id !=null;
                        })
                ])
            ]);

    }
}
