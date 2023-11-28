<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainResource\Pages;
use App\Filament\Resources\MainResource\RelationManagers;
use App\Models\Main;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class MainResource extends Resource
{
    protected static ?string $model = Main::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel='عقود';
    protected static ?string $navigationGroup='ادخال وتعديل عقود';


    public static function form(Form $form): Form
    {
        return $form


            ->schema([

              Select::make('bank_id')
                ->label('المصرف')
                ->relationship('Bank','BankName')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  TextInput::make('BankName')
                    ->required()
                    ->label('اسم المصرف')
                    ->maxLength(255),
                  Select::make('taj_id')
                    ->relationship('Taj','TajName')
                    ->label('المصرف التجميعي')
                    ->searchable()
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
                ->required(),
              Select::make('customer_id')
                ->label('الزبون')
                ->relationship('Customer','cusName')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  Forms\Components\Section::make('Publishing')
                    ->description('Settings for publishing this post.')
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
              TextInput::make('notes')
                ->label('ملاحظات')

                ->columnSpan(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table

          ->recordClasses(fn (Model $record) => match ($record->sul) {
            '100' => 'leading-3 p-0 h-4 text-xs',

            default => ' text-xs text-blue-100',
          })
            ->columns([
              TextColumn::make('Customer.CusName'),

              TextColumn::make('sul')
               ,
              TextColumn::make('kst')
                ,

            ])
            ->filters([
                //
            ])
            ->actions([

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
       //     'create' => Pages\CreateMain::route('/create'),
         //   'edit' => Pages\EditMain::route('/{record}/edit'),
        ];
    }
}
