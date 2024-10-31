<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Enums\Tar_type;
use App\Filament\Resources\TarKstResource\Pages;
use App\Filament\Resources\TarKstResource\RelationManagers;
use App\Livewire\Traits\AksatTrait;
use App\Models\TarKst;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TarKstResource extends Resource
{
    use AksatTrait;
    protected static ?string $model = TarKst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='اقساط مرجعة';
    protected static ?int $navigationSort = 6;

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
                TextColumn::make('tarkstable.name')
                    ->searchable()
                    ->label('الاسم'),
                TextColumn::make('tar_date')
                    ->searchable()
                    ->sortable()
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('tar_type')
                    ->label('البيان'),
                TextColumn::make('haf_id')
                    ->label('رقم الحافظة'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                 ->modalHeading('الغاء الترجيع')
                 ->after(function (Model $record){
                     if ($record->tar_type==Tar_type::من_الخطأ){
                         $record->tarkstable->status=Status::غير_مرجع;
                         $record->tarkstable->save();
                     }
                     if ($record->tar_type==Tar_type::من_قسط_مخصوم){
                         self::StoreTran2($record->tarkstable->id,$record->tar_date,$record->kst,$record->haf_id);
                     }
                 }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('الغاء الترجيع')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            foreach ($records as  $item){
                                if ($item->tar_type==Tar_type::من_الخطأ){
                                    $item->tarkstable->status=Status::غير_مرجع;
                                    $item->tarkstable->save();
                                    $item->delete();
                                }
                                if ($item->tar_type==Tar_type::من_قسط_مخصوم){
                                    self::StoreTran2($item->tarkstable->id,$item->tar_date,$item->kst,$item->haf_id);
                                }
                            }

                        }),
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
            'index' => Pages\ListTarKsts::route('/'),
            'create' => Pages\CreateTarKst::route('/create'),
            'edit' => Pages\EditTarKst::route('/{record}/edit'),
        ];
    }
}
