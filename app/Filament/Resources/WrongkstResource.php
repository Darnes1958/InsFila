<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Enums\Tar_type;
use App\Filament\Resources\WrongkstResource\Pages;
use App\Filament\Resources\WrongkstResource\RelationManagers;
use App\Livewire\Traits\AksatTrait;
use App\Livewire\Traits\MainTrait;
use App\Models\Main;
use App\Models\Tarkst;
use App\Models\Tran;
use App\Models\Wrongkst;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use DateTime;


class WrongkstResource extends Resource
{
    use MainTrait;
    use AksatTrait;
    protected static ?string $model = Wrongkst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='أقساط واردة بالخطأ';
    protected static ?int $navigationSort = 4;
    public static function getNavigationBadge(): ?string
    {
        return Wrongkst::count();
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                 ->label('الاسم'),
                TextColumn::make('Taj.TajName')
                    ->searchable()
                    ->sortable()
                    ->label('المصرف'),
                TextColumn::make('acc')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->label('رقم الحساب'),
                TextColumn::make('wrong_date')
                    ->sortable()
                   ->label('التاريخ'),
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('status')
                    ->label('الحالة'),

            ])
            ->recordUrl(
                null
            )
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => $record->status->value === 1,
            )
            ->filters([
                SelectFilter::make('taj_id')
                    ->relationship('Taj','TajName')
                    ->label('مصارف'),
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->label('الحالة'),

            ])
            ->actions([
                Tables\Actions\Action::make('toMain')
                 ->label('تصحيح')
                ->icon('heroicon-o-check')
                ->iconButton()
                ->visible(function (Model $record): bool {
                    return $record->status->value==1;
                })
                ->color('success')
                ->form([
                        Forms\Components\Select::make('main_id')
                         ->label('العقد')
                         ->options(function (Model $record) {
                             return Main::where('taj_id',$record->taj_id)->join('customers','customers.id','mains.customer_id')
                                 ->pluck('customers.name', 'mains.id');
                         })

                        ->searchable()
                        ->preload()
                        ->required()
                    ])
                ->action(function (Model $record,array $data) {
                    $wrong=Wrongkst::where('acc',$record->acc)->get();
                    foreach ($wrong as $wr) {
                        $res= Tran::create([
                            'main_id'=>$data['main_id'],
                            'ksm'=>$wr->kst,
                            'ksm_type_id'=>2,
                            'ksm_date'=>$wr->wrong_date,
                            'user_id'=>Auth::id(),
                            'ser'=>Tran::where('main_id',$data['main_id'])->max('ser')+1,
                            'kst_date'=>self::getKst_date2($data['main_id']),
                            'haf_id'=>$wr->haf_id,
                        ]);
                        $wr->status=3;
                        $wr->save();
                    }
                    Main::find($data['main_id'])->update(['acc'=>$record->acc]);
                    self::MainTarseed2($data['main_id']);
                })
            ])
            ->bulkActions([
                BulkAction::make('ترجيع')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        foreach ($records as  $item){
                            $item->tarkst()->create([
                                'tar_date' => date('Y-m-d'),
                                'kst' => $item->kst,
                                'tar_type' => Tar_type::من_الخطأ,
                                'haf_id' => $item->haf_id,
                                'user_id' => Auth::id(),
                            ]);
                            $item->status=2;
                            $item->save();
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
            'index' => Pages\ListWrongksts::route('/'),
            'create' => Pages\CreateWrongkst::route('/create'),
            'edit' => Pages\EditWrongkst::route('/{record}/edit'),
        ];
    }
}
