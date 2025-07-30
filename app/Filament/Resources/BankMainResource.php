<?php

namespace App\Filament\Resources;

use App\Enums\R_type;
use App\Filament\Resources\BankMainResource\Pages;
use App\Filament\Resources\BankMainResource\RelationManagers;
use App\Models\BankMain;
use App\Models\Taj;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankMainResource extends Resource
{
    protected static ?string $model = BankMain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel='المصرف الأم';
    protected static ?string $navigationGroup='مصارف';
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('اسم المصرف'),
                Radio::make('r_type')
                    ->options(R_type::class)
                    ->inline()
                    ->inlineLabel(false)

                    ->required()
                    ->label('نوع الخصم'),
                TextInput::make('ratio')
                    ->numeric()
                    ->required()
                    ->label('النسبة'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('الرقم الألي'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('اسم المصرف'),
                TextColumn::make('r_type')
                    ->sortable()
                    ->searchable()
                    ->label('نوع الخصم'),
                TextColumn::make('ratio')
                    ->searchable()
                    ->sortable()
                    ->label('النسبة'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->visible(fn(Model $record): bool =>!Taj::where('bank_main_id',$record->id)->exists()),
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
            'index' => Pages\ListBankMains::route('/'),
            'create' => Pages\CreateBankMain::route('/create'),
            'edit' => Pages\EditBankMain::route('/{record}/edit'),
        ];
    }
}
