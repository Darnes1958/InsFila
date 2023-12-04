<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainResource\Pages;

use App\Models\Main;

use App\Models\Main_arc;
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


class MainResource extends Resource
{
    protected static ?string $model = Main::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel='عقود';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
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
                ->relationship('Customer','cusName')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  Forms\Components\Section::make('ادخال زبائن')
                    ->description('يجب ادخال اسم الزبون والبيانات الاخري اختيارية')
                    ->schema([
                      TextInput::make('CusName')
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
                      TextInput::make('CusName')
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
                ->required(),

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
                    ->relationship('Sell','item_name')
                    ->preload()
                ->createOptionForm([
                  Forms\Components\Section::make('ادخال بضاعة')
                    ->description('ادخال بيانات بضاعة او اصناف جديدة')

                    ->schema([
                      TextInput::make('item_name')
                        ->required()
                        ->label('اسم البضاعة')
                        ->maxLength(255),
                    ])


                ])
                ->editOptionForm([
                  Forms\Components\Section::make('ادخال بضاعة')
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


            ]);
    }


    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('Customer.CusName')->label('الاسم')->searchable()->sortable(),

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

                ViewAction::make('View Information')->iconButton()->color('primary'),
                DeleteAction::make()->iconButton(),

                EditAction::make()->iconButton()->color('blue'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'view' => Pages\ViewMain::route('/{record}'),
        ];
    }
}
