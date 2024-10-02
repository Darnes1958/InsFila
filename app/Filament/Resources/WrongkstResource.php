<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrongkstResource\Pages;
use App\Filament\Resources\WrongkstResource\RelationManagers;
use App\Models\Wrongkst;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class WrongkstResource extends Resource
{
    protected static ?string $model = Wrongkst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                TextColumn::make('name'),
                TextColumn::make('Bank.name'),
                TextColumn::make('acc'),
                TextColumn::make('kst')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWrongksts::route('/'),
            'create' => Pages\CreateWrongkst::route('/create'),
            'edit' => Pages\EditWrongkst::route('/{record}/edit'),
        ];
    }
}
