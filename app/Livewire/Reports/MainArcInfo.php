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
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
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
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
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
    public $main_id;
  public Main_arc $mainRec;

  public MainForm $mainForm;
  public TransForm $transForm;
  public OverForm $overForm;

    public function mount()
    {
        $this->mainId=Main_arc::min('id');
        $this->main_id=$this->mainId;
        $this->mainRec=Main_arc::find($this->mainId);
        $this->form->fill([]);
    }
    public function Do(Get $get,Set $set)
    {
        if (!Main_arc::find($this->main_id))
            Notification::make()
                ->color('danger')
                ->title('هذا الرقم غير مخزون')->danger()->send();
    }
  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('mainId')
          ->options(Main_arc::all()->pluck('Customer.name', 'id')->toArray())
          ->searchable()
          ->live()
          ->Label('بحث')


          ->afterStateUpdated(function ($state,Set $set) {
            if (Main_arc::where('id',$state)->exists())
            {
                $this->mainId=$state;
                $this->mainRec=Main_arc::find($this->main_id);
                $this->dispatch('Take_Main_Id',main_id: $this->mainId);
                $set('main_id',$this->mainId);
            }

            else $this->mainId=null;

          })
            ->columnSpan(2),
          TextInput::make('main_id')
              ->label('رقم العقد')
              ->columnSpan(1)
              ->live(onBlur: true)
              ->extraAttributes(['wire:keydown.enter' => 'Do',])
              ->afterStateUpdated(function ($state,Set $set){
                  if (Main_arc::where('id',$state)->exists()){
                      $this->mainId=$state;
                      $this->mainRec=Main_arc::find($this->main_id);
                      $set('mainId',$state);
                      $this->dispatch('Take_Main_Id',main_id: $this->mainId);
                  }


              }),
          Actions::make([
              Action::make('retrieve')
              ->color('primary')
              ->requiresConfirmation()
              ->action(function (){
                  $this->DoArc();
              })
              ->label('استرجاع من الأرشيف')
          ])->columnSpan(1)->verticalAlignment(VerticalAlignment::End),
      ])->columns(4);
  }

  public function mainArcInfolist(Infolist $infolist): Infolist
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
            TextEntry::make('notes')->label('ملاحظات')
                ->visible(fn(): bool=>filled($this->mainRec->notes))->columnSpanFull(),

        ])->columns(8);
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Trans_arc $tran)  {
          $tran=Trans_arc::where('main_id',$this->mainId);
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

        $record=Main_arc::find($this->mainId);
        $oldRecord= $record;
        $newRecord = $oldRecord->replicate();

        $newRecord->setTable('mains');
        $newRecord->id=$record->id;

        $newRecord->save();
        Overkst::where('overkstable_type','App\Models\Main_arc')
            ->where('overkstable_id',$record->id)
            ->update(['overkstable_type'=>'App\Models\Main']);

        Trans_arc::query()
            ->where('main_id', $record->id)
            ->each(function ($oldTran) {
                $newTran = $oldTran->replicate();
                $newTran->setTable('trans');
                $newTran->save();
                $oldTran->delete();
            });
        $record->delete();
        $this->mainRec=Main_arc::first();
        $this->mainId=$this->mainRec->id;
        $this->main_id=$this->mainId;
        $this->dispatch('Take_Main_Id',main_id: $this->main_id);
        Notification::make()
            ->title('تم النقل بنجاح')
            ->success()
            ->send();

      DB::connection(Auth()->user()->company)->commit();
    } catch (\Exception $e) {
      info($e);
      Notification::make()
          ->title('حدث خطأ')
          ->send();
      DB::connection(Auth()->user()->company)->rollback();
    }
   $this->form($this->form);

  }

  public function render()
    {
      return view('livewire.reports.main-arc-info');
    }
}
