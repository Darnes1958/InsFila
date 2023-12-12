<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

final class  MainForm
{
    public static function schema(): array
    {
        return [
            Grid::make(4)
                ->schema([
                    Select::make('bank_id')
                        ->columnSpan(2)
                        ->label('المصرف')
                        ->relationship('Bank','BankName')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('BankName')
                                ->required()
                                ->label('اسم المصرف'),

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
                        ->columnSpan(2)
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

                        ->columnSpan(4)
                        ->label('ملاحظات')

                ])

        ];
    }
}
