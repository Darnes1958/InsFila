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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
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
  public $mainId;


  public Main $mainRec;
  public $montahy=false;
  public MainForm $mainForm;
  public TransForm $transForm;
  public OverForm $overForm;

  public function mount()
  {
      $this->mainId=Main::min('id');
      $this->main_id=$this->mainId;
      $this->mainRec=Main::find($this->mainId);
      $this->montahy=$this->mainRec->raseed<=0;
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
        ->columnSpan(2)
         ->relationship('Main', 'id')
          ->getOptionLabelFromRecordUsing(fn (Main $record) => "{$record->Customer->name} {$record->acc}")
          ->live()
          ->searchable()
          ->preload()
          ->Label('بحث')
          ->afterStateUpdated(function (Get $get,Set $set) {

            if (Main::where('id',$get('main_id'))->exists())
            {
                $this->main_id=$get('main_id');
                $this->mainRec=Main::find($this->main_id);
                $this->dispatch('Take_Main_Id',main_id: $this->main_id);
                $set('mainId',$this->main_id);
            }

            else $this->main_id=null;
          }),
          TextInput::make('mainId')
          ->label('رقم العقد')
          ->columnSpan(1)
          ->live(onBlur: true)

          ->afterStateUpdated(function ($state,Set $set){
              if (Main::where('id',$state)->exists()){
                  $this->main_id=$state;
                  $this->mainRec=Main::find($this->main_id);
                  $set('mainId',$state);
                  $this->dispatch('Take_Main_Id',main_id: $this->main_id);
              }


          }),
      ])
      ->columns(3)  ;
  }

  public function mainInfolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->record($this->mainRec)
      ->schema([

              TextEntry::make('Customer.name')
                ->label(new HtmlString('<div class="text-primary-400 text-lg font-extrabold">اسم الزبون</div>'))
                ->color('info')->size(TextEntry\TextEntrySize::Large)
                ->weight(FontWeight::ExtraBold)
                ->columnSpan(3),
              TextEntry::make('Bank.BankName')
                ->label('المصرف')
                  ->columnSpan(3)
                ->color('info'),
              TextEntry::make('acc')->label('رقم الحساب')
                  ->columnSpan(2)
                ->color('info'),
              TextEntry::make('id')
                ->columnSpan(2)
                ->label(new HtmlString('<div class="text-primary-400 text-lg">رقم العقد</div>'))
                ->color('info')
                ->weight(FontWeight::ExtraBold)
                ->size(TextEntry\TextEntrySize::Large),
              TextEntry::make('sul_begin')->label('تاريخ العقد')->columnSpan(2),
              TextEntry::make('sul')->label('قيمة العقد')->color('info')->columnSpan(2),

              TextEntry::make('kst_count')->label('عدد الأقساط')->columnSpan(2),
              TextEntry::make('kst')->label('القسط')->columnSpan(2),
              TextEntry::make('pay')->label('المدفوع')->columnSpan(2),
              TextEntry::make('raseed')->label('المتبقي')->color('danger')
                  ->weight(FontWeight::ExtraBold)->columnSpan(2),


              TextEntry::make('LastKsm')->label('تاريخ اخر خصم')->columnSpan(2),

              TextEntry::make('over_count')->label('اقساط بالفائض')->color('danger')
                  ->weight(FontWeight::ExtraBold)
                ->visible(fn(): bool=>$this->mainRec->overkstable()->exists())->columnSpan(2),
              TextEntry::make('over_kst')->label('قيمتها')
                  ->visible(fn(): bool=>$this->mainRec->overkstable()->exists())->columnSpan(2),
              TextEntry::make('tar_count')->label('اقساط مرجعة')->color('danger')
                  ->weight(FontWeight::ExtraBold)
                  ->visible(fn(): bool=>$this->mainRec->tarkst()->exists())->columnSpan(2),
              TextEntry::make('tar_kst')->label('قيمتها')
                  ->visible(fn(): bool=>$this->mainRec->tarkst()->exists())->columnSpan(2),

      ])->columns(8);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Tran $tran)  {
          $tran=Tran::where('main_id',$this->main_id);
        return  $tran;
      })
      ->columns([
          TextColumn::make('ser')

              ->color('primary')
              ->sortable()
              ->label('ت'),
        TextColumn::make('kst_date')->sortable()
            ->toggleable()
          ->label('تاريخ القسط'),
        TextColumn::make('ksm_date')->sortable()
          ->label('تاريخ الخصم'),
        TextColumn::make('ksm')
          ->label('الخصم'),
          TextColumn::make('ksm_type_id')
              ->size(TextColumnSize::ExtraSmall)
              ->toggleable()
              ->toggledHiddenByDefault()
              ->label('طريقة الدفع'),
          TextColumn::make('ksm_notes')
              ->toggleable()
              ->toggledHiddenByDefault()
              ->size(TextColumnSize::ExtraSmall)
              ->label('ملاحظات'),
      ])
      ->striped();
  }

  public function DoArc(){
    DB::connection(Auth()->user()->company)->beginTransaction();
    try {
        $record=Main::find($this->main_id);
        $oldRecord= $record;
        $newRecord = $oldRecord->replicate();

        $newRecord->setTable('main_arcs');
        $newRecord->id=$record->id;

        $newRecord->save();
        Overkst::where('overkstable_type','App\Models\Main')
            ->where('overkstable_id',$record->id)
            ->update(['overkstable_type'=>'App\Models\Main_arc']);

        Tran::query()
            ->where('main_id', $record->id)
            ->each(function ($oldTran) {
                $newTran = $oldTran->replicate();
                $newTran->setTable('trans_arcs');
                $newTran->save();
                $oldTran->delete();
            });
        $record->delete();
        $this->main_id=null;
        $this->dispatch('Take_Main_Id',main_id: $this->main_id);



      DB::connection(Auth()->user()->company)->commit();
    } catch (\Exception $e) {
      info($e);
      DB::connection(Auth()->user()->company)->rollback();
    }


  }

  public function render()
    {




        return view('livewire.reports.main-info');
    }
}
