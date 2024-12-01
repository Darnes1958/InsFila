<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HafithaResource\Pages;
use App\Filament\Resources\HafithaResource\RelationManagers;
use App\Models\Hafitha;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HafithaResource extends Resource
{
    protected static ?string $model = Hafitha::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='حوافظ';
    protected static ?int $navigationSort=9;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('الرقم الألي')->sortable()->searchable(),
                TextColumn::make('Taj.TajName')->label('رقم الحساب')->searchable()->sortable(),
                TextColumn::make('from_date')->label('تاريخ بداية الحافظ')->searchable()->sortable(),
                TextColumn::make('to_date')->label('تاريخ نهاية الحافظة')->searchable()->sortable(),
                TextColumn::make('tot')->label('الاجمالي')->searchable()->sortable(),
                TextColumn::make('morahel')->label('المرحل')->sortable(),
                TextColumn::make('over_kst')->label('الفائض')->sortable(),
                TextColumn::make('over_kst_arc')->label('الفائض من الارشيف')->sortable(),
                TextColumn::make('half')->label('الجزئي')->sortable(),
                TextColumn::make('wrong_kst')->label('بالخطأ')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
               //
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
            'index' => Pages\ListHafithas::route('/'),
            'create' => Pages\CreateHafitha::route('/create'),
            'edit' => Pages\EditHafitha::route('/{record}/edit'),
        ];
    }
}
