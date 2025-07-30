<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TajResource\Pages;
use App\Filament\Resources\TajResource\RelationManagers;
use App\Models\Bank;
use App\Models\Taj;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TajResource extends Resource
{
    protected static ?string $model = Taj::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $pluralLabel='المصرف التجميعي';

    protected static ?string $navigationGroup='مصارف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('TajName')
                    ->required()
                    ->label('اسم المصرف'),
                TextInput::make('TajAcc')
                    ->required()
                    ->label('رقم الحساب'),
                Select::make('bank_main_id')
                 ->required()
                 ->label('المصرف الأم')
                 ->relationship('BankMain', 'name')

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
                TextColumn::make('TajName')
                    ->searchable()
                    ->sortable()
                    ->label('اسم المصرف'),
                TextColumn::make('TajAcc')
                    ->sortable()
                    ->searchable()
                    ->label('رقم الحساب'),
                TextColumn::make('BankMain.name')
                    ->searchable()
                    ->sortable()
                    ->label('المصرف الأم'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->visible(fn(Model $record): bool =>!Bank::where('taj_id',$record->id)->exists()),
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
            'index' => Pages\ListTajs::route('/'),
            'create' => Pages\CreateTaj::route('/create'),
            'edit' => Pages\EditTaj::route('/{record}/edit'),
        ];
    }
}
