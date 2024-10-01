<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FromexcelResource\Pages;
use App\Filament\Resources\FromexcelResource\RelationManagers;
use App\Models\Fromexcel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FromexcelResource extends Resource
{
    protected static ?string $model = Fromexcel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }

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
                Tables\Columns\TextColumn::make('main_id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('acc')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('ksm_date'),
                Tables\Columns\TextColumn::make('ksm'),
                Tables\Columns\TextColumn::make('taj_id'),
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
            'index' => Pages\ListFromexcels::route('/'),
            'create' => Pages\CreateFromexcel::route('/create'),
            'edit' => Pages\EditFromexcel::route('/{record}/edit'),
        ];
    }
}
