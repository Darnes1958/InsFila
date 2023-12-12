<?php

namespace App\Livewire\Aksat;

use App\Models\Main;
use App\Models\Main_arc;
use App\Services\MainForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

use Illuminate\Support\HtmlString;
use Livewire\Component;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Section;
use Livewire\Attributes\On;


class DamegCont extends Component implements HasInfolists,HasForms
{
    use InteractsWithInfolists,InteractsWithForms;
    public Main $mainRec;
    public $the_main_id;

    public $show=false;
    public \App\Livewire\Forms\MainForm $data;
    public $acc='00000000';


    #[On('TakeMainId')]
    public function updateMainList($id)
    {
       $this->the_main_id=$id;
        $this->mainRec=Main::find($this->the_main_id);
        $this->show=true;
        $this->data->SetMain($this->the_main_id);

    }

    public function mount(Main $main): void
    {
        if ($this->the_main_id==null) $this->the_main_id=Main::min('id');
        $this->mainRec=Main::find($this->the_main_id);

       // $this->form->fill($main->find(17)->toArray());
    }
    public function create(): void
    {
        dd($this->form->getState());
    }
    public static function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Main::class)

            ->schema([
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

                Select::make('customer_id')
                    ->label('الزبون')
                    ->relationship('Customer','cusName')
                 ->disabled(),



                Select::make('bank_id')
                    ->label('المصرف')
                    ->relationship('Bank','BankName')
                   ->disabled(),
                TextInput::make('acc')
                    ->label('رقم الحساب')
                    ->disabled(),

                DatePicker::make('sul_begin')
                    ->required()
                    ->label('تاريخ العقد')
                    ->maxDate(now())
                    ->default(now()),
                TextInput::make('sul')
                    ->label('قيمة العقد')
                    ->live(onBlur: true)

                    ->afterStateUpdated(function (Get $get,Set $set) {
                        if ($get('sul') && $get('kst_count') &&
                            !$get('kst') && $get('kst')!=0) {
                            $val = $get('sul') / $get('kst_count');
                            $set('kst', $val);
                        }
                    })
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
                Select::make('sell_id')
                    ->label('البضاعة')
                    ->relationship('Sell','item_name')
                    ->preload()
                    ->createOptionForm([
                        Section::make('ادخال بضاعة')
                            ->description('ادخال بيانات بضاعة او اصناف جديدة')

                            ->schema([
                                TextInput::make('item_name')
                                    ->required()
                                    ->label('اسم البضاعة')
                                    ->maxLength(255),
                            ])


                    ])
                    ->editOptionForm([
                        Section::make('ادخال بضاعة')
                            ->description('ادخال بيانات بضاعة او اصناف جديدة')
                            ->schema([
                                TextInput::make('item_name')
                                    ->required()
                                    ->label('اسم البضاعة')
                                    ->maxLength(255),
                            ])

                    ])
                    ->createOptionAction(fn ($action) => $action->color('success'))
                    ->editOptionAction(fn ($action) => $action->color('info'))

                    ->required()
                    ->default(1)
                    ->columnSpan(2),
                TextInput::make('notes')
                    ->label('ملاحظات')->columnSpanFull()


            ])
               ->columns(4)

            ])
            ])
            ;
    }

    public function mainInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->mainRec)
            ->schema([
                Group::make([
                    Section::make(new HtmlString('<div class="text-danger-600">بيانات العقد السابق</div>'))
                        ->schema([
                            TextEntry::make('sul')->label('قيمة العقد')->color('info'),
                            TextEntry::make('kst')->label('القسط'),
                            TextEntry::make('pay')->label('المدفوع'),
                            TextEntry::make('raseed')->label('المتبقي')->color('danger')->weight(FontWeight::ExtraBold),

                        ])->columns(4)
                ])
            ]);

    }

    public function render()
    {
        return view('livewire.aksat.dameg-cont');
    }
}
