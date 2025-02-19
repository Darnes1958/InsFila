<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankResource\Pages;
use App\Filament\Resources\BankResource\RelationManagers;
use App\Models\Bank;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='مصارف';
    protected static ?int $navigationSort=10;

    public static function form(Form $form): Form
    {
        return $form
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
                TextColumn::make('BankName')
                    ->searchable()
                    ->sortable()
                 ->label('اسم المصرف'),
                TextColumn::make('Taj.TajName')
                    ->searchable()
                    ->sortable()
                    ->label('المصرف التجميعي'),
                TextColumn::make('main_count')
                    ->searchable()
                    ->sortable()
                    ->counts('Main')
                    ->label('عدد العقود'),


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
            'index' => Pages\ListBanks::route('/'),
            'create' => Pages\CreateBank::route('/create'),
            'edit' => Pages\EditBank::route('/{record}/edit'),
        ];
    }
}
