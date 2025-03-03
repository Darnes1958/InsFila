<?php

namespace App\Filament\Resources;

use App\Filament\Pages\ContAllThing;
use App\Filament\Resources\MainResource\Pages;

use App\Models\aksat\kst_trans;
use App\Models\Main;

use App\Models\Main_arc;
use App\Models\Overkst;
use App\Models\OverTar\over_kst;
use App\Models\Sell;
use App\Models\Setting;
use App\Models\Tarkst;
use App\Models\Tran;
use App\Services\MainForm;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;


class MainResource extends Resource
{
    protected static ?string $model = Main::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $pluralModelLabel='عقود';
    protected static ?int $navigationSort = 1;


  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('ادخال عقود');
  }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('id')
                ->label('رقم العقد')
                ->required()
                ->unique(ignoreRecord: true)
                ->unique(table: Main_arc::class)
                ->default(Main::max('id')+1)
                ->autofocus()
                ->numeric(),

              Select::make('customer_id')
                ->afterStateUpdated( function (Forms\Set $set, ?string $state){
                  $rec=Main::where('customer_id',$state)->get();
                  if (count($rec)>0) {
                    $set('bank_id', $rec->first()->bank_id);
                    $set('acc', $rec->first()->acc);
                  } else
                  {
                    $rec=Main_arc::where('customer_id',$state)->get();
                    if (count($rec)>0) {
                      $set('bank_id', $rec->first()->bank_id);
                      $set('acc', $rec->first()->acc);
                    }
                  }

                })
                ->label('الزبون')
                ->relationship('Customer','name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  Forms\Components\Section::make('ادخال زبائن')
                    ->description('يجب ادخال اسم الزبون والبيانات الاخري اختيارية')
                    ->schema([
                      TextInput::make('name')
                        ->required()
                        ->label('اسم الزبون')
                        ->maxLength(255),
                      TextInput::make('address')
                        ->label('العنوان'),
                      TextInput::make('mdar')
                        ->label('مدار'),
                      TextInput::make('libyana')
                        ->label('لبيانا'),
                      TextInput::make('card_no')
                        ->label('رقم الهوية'),
                      TextInput::make('others')
                        ->label('الرقم الوطني'),

                    ])->columns(2)
                ])
                ->editOptionForm([
                  Forms\Components\Section::make('تعديل زبائن')
                    ->description('يجب ادخال اسم الزبون والبيانات الاخري اختيارية')
                    ->schema([
                      TextInput::make('name')
                        ->required()
                        ->label('اسم الزبون')
                        ->maxLength(255),
                      TextInput::make('address')
                        ->label('العنوان'),
                      TextInput::make('mdar')
                        ->label('مدار'),
                      TextInput::make('libyana')
                        ->label('لبيانا'),
                      TextInput::make('card_no')
                        ->label('رقم الهوية'),
                      TextInput::make('others')
                        ->label('الرقم الوطني'),

                    ])->columns(2)
                ])
                ->createOptionAction(fn ($action) => $action->color('success'))
                ->editOptionAction(fn ($action) => $action->color('info'))
                ->required(),


              Select::make('bank_id')
                ->label('المصرف')
                ->relationship('Bank','BankName')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  Forms\Components\Section::make('ادخال مصارف')
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
                  Forms\Components\Section::make('ادخال مصارف')
                    ->description('ادخال بيانات مصرف .. ويمكن ادخال المصرف التجميعي اذا كان غير موجود بالقائمة')
                    ->schema([
                      TextInput::make('BankName')
                        ->required()
                        ->label('اسم المصرف')
                        ->maxLength(255),

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

                ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set) {
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

                ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set) {
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
                    ->relationship('Sell','notes')
                    ->searchable()
                    ->preload()
                    ->required()
                ->createOptionForm([
                  Forms\Components\Section::make('ادخال بضاعة')
                    ->schema([
                      TextInput::make('notes')
                        ->required()
                        ->label('البيان')
                        ->maxLength(255),
                    ])
                ])
                ->editOptionForm([
                  Forms\Components\Section::make('ادخال بضاعة')
                    ->schema([
                      TextInput::make('notes')
                        ->required()
                        ->label('البيان ')
                        ->maxLength(255)
                        ->required(),
                        ])

                    ])
                ->createOptionAction(fn ($action) => $action->color('success'))
                ->editOptionAction(fn ($action) => $action->color('info'))
                ->columnSpan(2),
              TextInput::make('notes')
                ->label('ملاحظات')->columnSpanFull()
            ])

            ;
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                null
            )
            ->columns([
                TextColumn::make('id')->label('رقم العقد')->sortable()->searchable(),
                TextColumn::make('Customer.name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('Bank.BankName')->label('المصرف')->searchable()->sortable(),
                TextColumn::make('acc')->label('رقم الحساب')->searchable()->sortable(),
                TextColumn::make('sul')->label('الاجمالي')->sortable(),
                TextColumn::make('pay')->label('المسدد')->sortable(),
                TextColumn::make('raseed')->label('الرصيد')->sortable(),
            ])
            ->filters([
                SelectFilter::make('bank_id')
                 ->relationship('Bank','BankName')
                 ->label('مصارف'),
                Tables\Filters\Filter::make('المسددة')
                 ->query(fn(Builder $query): Builder=>$query->where('raseed','=',0))
            ])
            ->actions([

                DeleteAction::make()->iconButton()->hidden(! auth()->user()->can('الغاء عقود'))
                    ->iconSize(IconSize::Small)
              ->before(function (Main $record){
                Tran::where('main_id',$record->id)->delete();
                foreach ($record->overkstable as $item) $item->delete();
                foreach ($record->tarkst as $item) $item->delete();

              }),
                EditAction::make()->iconButton()->color('blue')
                    ->visible(
                        Auth::user()->can('تعديل عقود')
                        && ! Setting::find(Auth::user()->company)->is_together
                    )->iconSize(IconSize::Small)
                   ,
                Tables\Actions\Action::make('mainedit')
                    ->iconButton()
                    ->icon('heroicon-m-pencil')
                    ->color('info')
                    ->iconSize(IconSize::Small)
                    ->visible(
                        Auth::user()->can('تعديل عقود')
                        &&  Setting::find(Auth::user()->company)->is_together
                    )
                    ->url(fn(Model $record) => self::getUrl('mainedit', ['record' => $record])),
                Tables\Actions\Action::make('print')
                ->hiddenLabel()
                ->iconButton()->color('success')
                ->icon('heroicon-m-printer')
                    ->iconSize(IconSize::Small)
                ->url(fn (Main $record): string => route('pdfmaincont', $record)),
                Tables\Actions\Action::make('tran')
                    ->hiddenLabel()
                    ->iconButton()->color('primary')
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-m-eye')
                    ->url(fn (Main $record): string => route('filament.admin.pages.cont-all-thing', ['main_id'=>$record->id])),
                Tables\Actions\Action::make('toArc')
                    ->label('نقل للأرشيف')
                    ->color('primary')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()

                    ->visible(fn($record)=>$record->raseed<=0)
                    ->action(function (Main $record) {
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
                    })

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMains::route('/'),
            'create' => Pages\CreateMain::route('/create'),
            'edit' => Pages\EditMain::route('/{record}/edit'),

            'maincreate' => Pages\MainCreate::route('/maincreate'),
            'mainedit' => Pages\MainEdit::route('/{record}/mainedit'),
        ];
    }

}
