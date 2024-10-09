<?php

namespace App\Livewire\Reports;


use App\Livewire\Forms\MainForm;
use App\Livewire\Forms\OverForm;
use App\Livewire\Forms\TarForm;
use App\Livewire\Forms\TransForm;
use App\Models\Customer;
use App\Models\Main;

use App\Models\Main_arc;
use App\Models\Overkst;
use App\Models\Overkst_arc;
use App\Models\Tran;
use App\Models\Trans_arc;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Filament\Forms\Form;


class   MainInfo extends Component implements HasInfolists,HasForms,HasTable,HasActions
{
  use InteractsWithInfolists,InteractsWithForms,InteractsWithTable,InteractsWithActions;

  public $main_id;
  public Main $mainRec;
  public $montahy=false;
  public MainForm $mainForm;
  public TransForm $transForm;
  public OverForm $overForm;

  public function mount()
  {
      $this->form->fill([]);
  }
  public function printAction(): Action
  {
    return Action::make('print')
      ->label('طباعة')
      ->button()
      ->color('info')
      ->icon('heroicon-m-printer')
      ->color('info')
      ->url(fn (): string => route('pdfmain', ['id'=>$this->main_id]));
  }
  public function printContAction(): Action
  {
    return Action::make('print')
      ->label('طباعة نموذج العقد')
      ->button()
      ->color('info')
      ->icon('heroicon-m-printer')
      ->color('info')
      ->url(fn (): string => route('pdfmaincont', ['id'=>$this->main_id]));
  }

  public function form(Form $form): Form
  {
    return $form
      ->model(Tran::class)
      ->schema([
        Select::make('main_id')

          ->relationship('Main', 'id')
          ->getOptionLabelFromRecordUsing(fn (Main $record) => "{$record->Customer->name} {$record->acc}")
          ->live()
          ->searchable()
          ->preload()
          ->hiddenLabel()
          ->afterStateUpdated(function (Get $get) {
            info($get('main_id'));
            if (Main::where('id',$get('main_id'))->exists())
             $this->main_id=$get('main_id');
            else $this->main_id=null;
          }),
      ]);
  }

  public function mainInfolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->record($this->mainRec)
      ->schema([
        Group::make([
          Section::make(new HtmlString('<div class="text-danger-600">بيانات الزبون</div>'))
            ->schema([
              TextEntry::make('Customer.name')
                ->label(new HtmlString('<div class="text-primary-400 text-lg font-extrabold">اسم الزبون</div>'))
                ->color('info')->size(TextEntry\TextEntrySize::Large)
                ->weight(FontWeight::ExtraBold)
                ->columnSpan(2),
              TextEntry::make('Bank.BankName')
                ->label('المصرف')
                ->color('info'),
              TextEntry::make('acc')->label('رقم الحساب')
                ->color('info'),
              TextEntry::make('Customer.libyana')->label('لبيانا')
                ->color('Fuchsia'),
              TextEntry::make('Customer.mdar')->label('المدار')->color('grean')
                ->color('green'),
            ])->columns(3)->collapsible()
        ]),
        Group::make([
          Section::make('بيانات العقد')

            ->schema([
              TextEntry::make('id')
                ->columnSpan(2)
                ->label(new HtmlString('<div class="text-primary-400 text-lg">رقم العقد</div>'))
                ->color('info')
                ->weight(FontWeight::ExtraBold)
                ->size(TextEntry\TextEntrySize::Large),
              TextEntry::make('sul_begin')->label('تاريخ العقد'),
              TextEntry::make('sul')->label('قيمة العقد')->color('info'),

              TextEntry::make('kst_count')->label('عدد الأقساط'),
              TextEntry::make('kst')->label('القسط'),
              TextEntry::make('pay')->label('المدفوع'),
              TextEntry::make('raseed')->label('المتبقي')->color('danger')->weight(FontWeight::ExtraBold),
            ])->columns(4)->collapsible()
        ]),
        Group::make([
          Section::make('بيانات عامة')
            ->schema([
              TextEntry::make('LastKsm')->label('تاريخ اخر خصم')->columnSpan(2),
              TextEntry::make('NextKst')->label('تاريخ الخصم القادم')->columnSpan(2),
              TextEntry::make('over_count')->label('اقساط بالفائض'),
              TextEntry::make('over_kst')->label('قيمتها'),
              TextEntry::make('tar_count')->label('اقساط مرجعة'),
              TextEntry::make('tar_kst')->label('قيمتها'),
            ])->columns(4)->collapsible()
        ]),
      ]);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Tran $tran)  {
          $tran=Tran::where('main_id',$this->main_id);
        return  $tran;
      })
      ->columns([
        TextColumn::make('kst_date')->sortable()
          ->label('تاريخ القسط'),
        TextColumn::make('ksm_date')->sortable()
          ->label('تاريخ الخصم'),
        TextColumn::make('ksm')
          ->label('الخصم'),
      ])
      ->striped();
  }

  public function DoArc(){
    DB::connection(Auth()->user()->company)->beginTransaction();
    try {
        $this->mainForm->SetMain($this->main_id);
        Main_arc::create(
          $this->mainForm->all()
        );

        $res=Tran::where('main_id',$this->main_id)->get();

        foreach ($res as $item){
          $this->transForm->SetTrans($item);
          $this->transForm->user_id=$item->user_id;

          Trans_arc::create(
            $this->transForm->all()
          );

        }

        $res=Overkst::where('main_id',$this->main_id)->get();
        foreach ($res as $item){
          $this->overForm->SetOver($item);
          $this->overForm->user_id=$item->user_id;
          Overkst_arc::create(
            $this->overForm->all()
          );

        }

        $old=$this->main_id;
        $this->main_id=Main::latest()->first()->id;
      Overkst::where('main_id',$old)->delete();
      Tran::where('main_id',$old)->delete();
      Main::where('id',$old)->delete();


      DB::connection(Auth()->user()->company)->commit();
    } catch (\Exception $e) {
      info($e);
      DB::connection(Auth()->user()->company)->rollback();
    }


  }

  public function render()
    {

        if (!$this->main_id) $this->main_id=Main::latest()->first()->id;

        $this->mainRec=Main::where('id',$this->main_id)->first();
        $this->montahy=$this->mainRec->raseed<=0;

        return view('livewire.reports.main-info');
    }
}
