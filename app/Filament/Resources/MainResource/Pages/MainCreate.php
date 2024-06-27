<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;

use App\Models\Main;
use App\Models\Main_arc;
use App\Models\Sell;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class MainCreate extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = MainResource::class;
    protected static string $view = 'filament.resources.main-resource.pages.main-create';
    protected ?string $heading="";
    public $contData;
    public function mount(): void
    {
        $this->contForm->fill([]);
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(),[
           'contForm'=> $this->makeForm()
            ->model(Main::class)
            ->schema($this->getContFormSchema())
            ->statePath('mainData'),
        ]);
    }

    protected function getContFormSchema(): array
    {
        return [
          Section::make()
              ->schema([
                  Select::make('sell_id')
                      ->label('فاتورة المبيعات')
                      ->options(Sell::pluck('tot','id'))
                      ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->Customer->name} {$record->total}")
                      ->searchable()
                      ->preload()
                      ->required()
                      ->columnSpan(2),
                  TextInput::make('id')
                      ->label('رقم العقد')
                      ->required()
                      ->unique(ignoreRecord: true)
                      ->unique(table: Main_arc::class)
                      ->default(Main::max('id')+1)
                      ->autofocus()
                      ->numeric(),
                  Select::make('bank_id')
                      ->label('المصرف')
                      ->relationship('Bank','BankName')
                      ->searchable()
                      ->preload()
                      ->createOptionForm([
                          Section::make('ادخال مصارف')
                              ->description('ادخال بيانات مصرف .. ويمكن ادخال المصرف التجميعي اذا كان غير موجود بالقائمة')
                              ->schema([
                                  TextInput::make('BankName')
                                      ->required()
                                      ->label('اسم المصرف')
                                      ->maxLength(255),
                                  Select::make('taj_id')
                                      ->relationship('Taj','TajName')
                                      ->label('المصرف التجميعي')
                                      ->searchable()
                                      ->preload()
                                      ->createOptionForm([
                                          TextInput::make('TajName')
                                              ->required()
                                              ->label('المصرف التجميعي')
                                              ->maxLength(255),
                                          TextInput::make('TajAcc')
                                              ->label('رقم الحساب')
                                              ->required(),
                                      ])
                                      ->required(),
                              ])
                      ])
                      ->editOptionForm([
                          Section::make('ادخال مصارف')
                              ->description('ادخال بيانات مصرف .. ويمكن ادخال المصرف التجميعي اذا كان غير موجود بالقائمة')
                              ->schema([
                                  TextInput::make('BankName')
                                      ->required()
                                      ->label('اسم المصرف')
                                      ->maxLength(255),
                                  Select::make('taj_id')
                                      ->relationship('Taj','TajName')
                                      ->label('المصرف التجميعي')
                                      ->searchable()
                                      ->preload()
                                      ->createOptionForm([
                                          TextInput::make('TajName')
                                              ->required()

                                              ->label('المصرف التجميعي')
                                              ->maxLength(255),
                                          TextInput::make('TajAcc')
                                              ->label('رقم الحساب')
                                              ->required(),
                                      ])
                                      ->required(),
                              ])
                      ])
                      ->createOptionAction(fn ($action) => $action->color('success'))
                      ->editOptionAction(fn ($action) => $action->color('info'))
                      ->required(),
                  TextInput::make('acc')
                      ->label('رقم الحساب')
                      ->required()
                      ->extraAttributes([
                          'wire:keydown.enter'=>'$dispatch("goto", {test: "wrong_kst"})',
                      ]),
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
                  TextInput::make('notes')
                      ->label('ملاحظات')->columnSpanFull()
              ])
        ];
    }
}
