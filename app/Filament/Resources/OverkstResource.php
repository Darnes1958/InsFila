<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OverkstResource\Pages;
use App\Filament\Resources\OverkstResource\RelationManagers;
use App\Livewire\Forms\TarForm;
use App\Livewire\Traits\AksatTrait;
use App\Models\Overkst;
use App\Models\Tarkst;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Mod;

class OverkstResource extends Resource
{
    protected static ?string $model = Overkst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='خصم بالفائض';

    use AksatTrait;


    protected static ?int $navigationSort = 5;

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
                TextColumn::make('id')
                    ->label('الرقم الألي'),
                TextColumn::make('main_id')
                  ->label('رقم العقد'),
                TextColumn::make('Main.Customer.name')
                    ->searchable()
                    ->sortable()

                    ->label('الاسم'),
                TextColumn::make('over_date')
                    ->searchable()
                    ->sortable()

                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('status')
                    ->color(fn(Model $record): string=>$record->status==='مرجع'?'success':'danger')
                    ->label('الحالة'),
                TextColumn::make('haf_id')
                    ->label('رقم الحافظة'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                 ->visible(function (Model $record) {
                     return $record->haf_id==0;
                 }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function (Model $record) {
                        return $record->haf_id==0;
                    }),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => $record->status === 'غير مرجع',
            )
            ->bulkActions([
                BulkAction::make('ترجيع')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()

                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                            foreach ($records as  $item){
                                $res=Tarkst::create([
                                    'main_id' => $item->main_id,
                                    'tar_date' => date('Y-m-d'),
                                    'kst' => $item->kst,
                                    'tar_type' => 'من الفائض',
                                    'from_id' => $item->id,
                                    'haf_id' => $item->haf_id,
                                    'user_id' => Auth::id(),
                                ]);
                                $item->update(['tar_id'=>$res->id,'status'=>'مرجع']);
                            }

                    }),
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
            'index' => Pages\ListOverksts::route('/'),
            'create' => Pages\CreateOverkst::route('/create'),
            'edit' => Pages\EditOverkst::route('/{record}/edit'),
        ];
    }
}
