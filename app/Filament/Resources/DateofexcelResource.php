<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DateofexcelResource\Pages;
use App\Filament\Resources\DateofexcelResource\RelationManagers;
use App\Models\Dateofexcel;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DateofexcelResource extends Resource
{
    protected static ?string $model = Dateofexcel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup='Setting';
    protected static ?int $navigationSort=6;
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
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Taj.TajName')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_begin')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_end')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('التجميعي')
                 ->relationship('Taj','TajName')
            ],Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListDateofexcels::route('/'),
            'create' => Pages\CreateDateofexcel::route('/create'),
            'edit' => Pages\EditDateofexcel::route('/{record}/edit'),
        ];
    }
}
