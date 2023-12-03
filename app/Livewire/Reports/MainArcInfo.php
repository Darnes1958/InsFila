<?php

namespace App\Livewire\Reports;


use App\Livewire\Forms\MainForm;
use App\Livewire\Forms\OverForm;
use App\Livewire\Forms\TarForm;
use App\Livewire\Forms\TransForm;
use App\Models\Main;

use App\Models\Main_arc;
use App\Models\Overkst;
use App\Models\Overkst_arc;
use App\Models\Tran;
use App\Models\Trans_arc;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Filament\Forms\Form;


class   MainArcInfo extends Component implements HasInfolists,HasForms,HasTable
{
  use InteractsWithInfolists,InteractsWithForms,InteractsWithTable;

  public $mainId;
  public Main_arc $mainRec;

  public MainForm $mainForm;
  public TransForm $transForm;
  public OverForm $overForm;


  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('mainId')
          ->options(Main_arc::all()->pluck('Customer.CusName', 'id')->toArray())
          ->searchable()
          ->reactive()
          ->hiddenLabel()


          ->afterStateUpdated(function (callable $get) {
            if (Main_arc::where('id',$get('mainId'))->exists())
             $this->mainId=$get('mainId');
            else $this->mainId=null;

          }),
      ]);
  }

  public function mainArcInfolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->record($this->mainRec)
      ->schema([
        Group::make([
          Section::make(new HtmlString('<div class="text-danger-600">بيانات الزبون</div>'))
            ->schema([
              TextEntry::make('Customer.CusName')
                ->label(new HtmlString('<div class="text-primary-400 text-lg">اسم الزبون</div>'))
                ->color('info')->size(TextEntry\TextEntrySize::Large)
                ->columnSpan(2),
              TextEntry::make('Bank.BankName')
                ->label('المصرف')
                ->color('info'),
              TextEntry::make('acc')->label('رقم الحساب')
                ->color('info'),
              TextEntry::make('libyana')->label('لبيانا')
                ->color('Fuchsia'),
              TextEntry::make('mdar')->label('المدار')->color('grean')
                ->color('green'),
            ])->columns(3)->collapsible()
        ]),
        Group::make([
          Section::make('بيانات العقد')

            ->schema([
              TextEntry::make('id')
                ->columnSpanFull()
                ->label(new HtmlString('<div class="text-primary-400 text-lg">رقم العقد</div>'))
                ->color('info')
                ->size(TextEntry\TextEntrySize::Large),
              TextEntry::make('sul_begin')->label('تاريخ العقد'),
              TextEntry::make('sul')->label('قيمة العقد')->color('info'),

              TextEntry::make('kst_count')->label('عدد الأقساط'),
              TextEntry::make('kst')->label('القسط'),
              TextEntry::make('pay')->label('المدفوع'),
              TextEntry::make('raseed')->label('المتبقي')->color('danger'),
            ])->columns(3)->collapsible()
        ]),
        Group::make([
          Section::make('بيانات عامة')
            ->schema([
              TextEntry::make('LastKsm')->label('تاريخ اخر خصم')->columnSpan(2),
              TextEntry::make('NextKst')->label('تاريخ الخصم القادم')->columnSpan(2),
              TextEntry::make('over_count')->label('اقسط بالفائض'),
              TextEntry::make('over_kst')->label('قيمتها'),
              TextEntry::make('tar_count')->label('اقساط مرجعة'),
              TextEntry::make('tar_kst')->label('ثيمتها'),
            ])->columns(4)->collapsible()
        ]),
      ]);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Trans_arc $tran)  {
          $tran=Trans_arc::where('main_id',$this->mainId);
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
        $this->mainForm->SetMain_arc($this->mainId);
        Main::create(
          $this->mainForm->all()
        );

        $res=Trans_arc::where('main_id',$this->mainId)->get();
        foreach ($res as $item){
          $this->transForm->SetTransArc($item);
          $this->transForm->user_id=$item->user_id;
          Tran::create(
            $this->transForm->all()
          );

        }

        $res=Overkst_arc::where('main_id',$this->mainId)->get();
        foreach ($res as $item){
          $this->overForm->SetOverArc($item);
          $this->overForm->user_id=$item->user_id;
          Overkst::create(
            $this->overForm->all()
          );

        }

        $old=$this->mainId;
        $this->mainId=Main_arc::latest()->first()->id;
        Overkst_arc::where('main_id',$old)->delete();
        Trans_arc::where('main_id',$old)->delete();
        Main_arc::where('id',$old)->delete();

      DB::connection(Auth()->user()->company)->commit();
    } catch (\Exception $e) {
      info($e);
      DB::connection(Auth()->user()->company)->rollback();
    }
   $this->form($this->form);

  }

  public function render()
    {

        if (!$this->mainId) $this->mainId=Main_arc::latest()->first()->id;

        $this->mainRec=Main_arc::where('id',$this->mainId)->first();


      return view('livewire.reports.main-arc-info');
    }
}
