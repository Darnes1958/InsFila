<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OurCompanyResource\Pages;
use App\Filament\Resources\OurCompanyResource\RelationManagers;
use App\Models\OurCompany;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class OurCompanyResource extends Resource
{
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->id()==1;
  }
    protected static ?string $model = OurCompany::class;
    protected static ?string $navigationGroup='Setting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('Company')->unique()->required(),
              TextInput::make('CompanyName')->unique()->required(),
              TextInput::make('CompanyNameSuffix')->required(),
              TextInput::make('CompCode')->unique()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('Company'),
              TextColumn::make('CompanyName'),
              TextColumn::make('CompanyNameSuffix'),
              TextColumn::make('CompCode'),
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
            'index' => Pages\ListOurCompanies::route('/'),
            'create' => Pages\CreateOurCompany::route('/create'),
            'edit' => Pages\EditOurCompany::route('/{record}/edit'),
        ];
    }
}
