<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Enums\Tar_type;
use App\Filament\Resources\OverkstResource\Pages;
use App\Filament\Resources\OverkstResource\RelationManagers;
use App\Livewire\Forms\TarForm;
use App\Livewire\Traits\AksatTrait;
use App\Livewire\Traits\PublicTrait;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\Overkst;
use App\Models\Tarkst;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
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

    use AksatTrait,PublicTrait;


    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                 ->schema([
                   MorphToSelect::make('overkstable')
                     ->types([
                        MorphToSelect\Type::make(Main::class)
                            ->getOptionLabelFromRecordUsing(fn (Main $record): string => "{$record->Customer->name} {$record->sul}")
                            ->label('العقود القائمة'),
                         MorphToSelect\Type::make(Main_arc::class)
                             ->getOptionLabelFromRecordUsing(fn (Main_arc $record) => "{$record->Customer->name} {$record->sul}")
                             ->label('الأرشيف'),
                     ])
                     ->searchable()
                     ->preload()
                     ->label('فائض من'),
                     //  self::getMainSelectFromComponent(),
                     self::getDateFromComponent(),
                     self::getKstFromComponent(),
                     Hidden::make('user_id')
                         ->default(Auth::id())
                 ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('id')
                    ->label('الرقم الألي'),

                TextColumn::make('overkstable.Customer.name')
                    ->label('الاسم'),

                TextColumn::make('over_date')
                    ->searchable()
                    ->sortable()
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('status')
                    ->label('الحالة'),
                TextColumn::make('overkstable_type')
                    ->label('حالة العقد'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                 ->visible(function (Model $record) {
                     return $record->haf_id==0 && $record->status==Status::غير_مرجع;
                 }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function (Model $record) {
                        return $record->haf_id==0 && $record->status==Status::غير_مرجع;
                    }),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => $record->status->value ==1,
            )
            ->bulkActions([
                BulkAction::make('ترجيع')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()

                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                            foreach ($records as  $item){
                                $item->tarkst()->create([
                                    'main_id' => $item->overkstable_id,
                                    'tar_date' => date('Y-m-d'),
                                    'kst' => $item->kst,
                                    'tar_type' => Tar_type::من_الفائض,
                                    'haf_id' => $item->haf_id,
                                    'user_id' => Auth::id(),
                                ]);

                                $item->update(['status'=>Status::مرجع]);

                                $count=Tarkst::where('main_id',$item->main_id)->count();
                                $sum=Tarkst::where('main_id',$item->main_id)->sum('kst');
                                Main::where('id',$item->main_id)->update([
                                    'tar_count'=>$count,
                                    'tar_kst'=>$sum,
                                ]);
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
