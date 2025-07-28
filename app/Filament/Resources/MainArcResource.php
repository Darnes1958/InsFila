<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainArcResource\Pages;
use App\Filament\Resources\MainArcResource\RelationManagers;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\MainArc;
use App\Models\Overkst;
use App\Models\Setting;
use App\Models\Tarkst;
use App\Models\Tran;
use App\Models\Trans_arc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MainArcResource extends Resource
{
    protected static ?string $model = Main_arc::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='الأرشيف';
    protected static ?int $navigationSort=8;
    public static function getNavigationBadge(): ?string
    {
        return Main_arc::count();
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
            ->recordUrl(
                null
            )
            ->columns([
                TextColumn::make('id')->label('رقم العقد')->sortable()->searchable(),
                TextColumn::make('Customer.name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('Bank.BankName')->label('المصرف')->searchable()->sortable(),
                TextColumn::make('acc')->label('رقم الحساب')->searchable()->sortable(),
                TextColumn::make('sul')->label('الاجمالي')->sortable(),
                TextColumn::make('pay')->label('المسدد')->sortable(),
                TextColumn::make('raseed')->label('الرصيد')->sortable(),
            ])
            ->filters([
                SelectFilter::make('bank_id')
                    ->relationship('Bank','BankName')
                    ->label('مصارف'),

            ])
            ->actions([
                Tables\Actions\Action::make('tran')
                    ->hiddenLabel()
                    ->iconButton()->color('primary')
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-m-eye')
                    ->url(fn (Main_arc $record): string => route('filament.admin.pages.cont-all-thing-arc', ['main_id'=>$record->id])),
                Tables\Actions\Action::make('toMain')
                    ->label('استرجاع')
                    ->color('primary')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->action(function (Main_arc $record) {
                        $oldRecord= $record;
                        $newRecord = $oldRecord->replicate();
                        $newRecord->setTable('mains');
                        $newRecord->id=$record->id;
                        $newRecord->save();
                        Trans_arc::query()
                            ->where('main_id', $record->id)
                            ->each(function ($oldTran) {
                                $newTran = $oldTran->replicate();
                                $newTran->setTable('trans');
                                $newTran->save();
                                $oldTran->delete();
                            });
                        $record->delete();
                    })

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
            'index' => Pages\ListMainArcs::route('/'),
            'create' => Pages\CreateMainArc::route('/create'),
            'edit' => Pages\EditMainArc::route('/{record}/edit'),
        ];
    }
}
