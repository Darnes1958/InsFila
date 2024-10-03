<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrongkstResource\Pages;
use App\Filament\Resources\WrongkstResource\RelationManagers;
use App\Livewire\Traits\AksatTrait;
use App\Models\Main;
use App\Models\Tarkst;
use App\Models\Tran;
use App\Models\Wrongkst;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
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

class WrongkstResource extends Resource
{
    protected static ?string $model = Wrongkst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationLabel='أقساط واردة بالخطأ';
    protected static ?int $navigationSort = 4;
    public static function setMonth($begin){
        $month = date('m', strtotime($begin));
        $year = date('Y', strtotime($begin));
        $date=$year.$month.'28';
        $date = DateTime::createFromFormat('Ymd',$date);
        $date=$date->format('Y-m-d');
        return $date;
    }
    public static function getKst_date($main_id){
        $res=Tran::where('main_id',$main_id)->get();
        if (count($res)>0) {
            $date=$res->max('kst_date');
            $date= date('Y-m-d', strtotime($date . "+1 month"));
            return $date;
        } else
        {
            $begin=Main::find($main_id)->sul_begin;

            return self::setMonth($begin);

        }
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
                TextColumn::make('kst')
                    ->label('المبلغ'),
                TextColumn::make('status')
                    ->label('المبلغ'),

            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => $record->status === 'غير مرجع',
            )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('toMain')
                 ->label('تصحيح')
                ->icon('heroicon-o-check')
                ->iconButton()
                ->visible(function (Model $record): bool {
                    return $record->status=='غير مرجع';
                })
                ->color('success')
                    ->form([
                        Forms\Components\Select::make('main_id')
                         ->label('العقد')
                         ->options(function (Model $record) {
                             return Main::where('taj_id',$record->taj_id)->join('customers','customers.id','mains.customer_id')
                                 ->pluck('name', 'mains.id');
                         })

                        ->searchable()
                        ->preload()
                        ->required()
                    ])
                ->action(function (Model $record,array $data) {
                    $res= Tran::create([
                        'main_id'=>$data['main_id'],
                        'ksm'=>$record->kst,
                        'ksm_type_id'=>2,
                        'ksm_date'=>$record->wrong_date,
                        'user_id'=>Auth::id(),
                        'ser'=>Tran::where('main_id',$data['main_id'])->max('ser')+1,
                        'kst_date'=>self::getKst_date($data['main_id']),
                        'haf_id'=>$record->haf_id,
                    ]);

                    Main::find($data['main_id'])->update(['acc'=>$record->acc]);
                    $record->status='مصحح';
                    $record->save();


                })
            ])
            ->bulkActions([
                BulkAction::make('ترجيع')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()

                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        foreach ($records as  $item){
                            $res=Tarkst::create([
                                'tar_date' => date('Y-m-d'),
                                'kst' => $item->kst,
                                'tar_type' => 'من الخطأ',
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
            'index' => Pages\ListWrongksts::route('/'),
            'create' => Pages\CreateWrongkst::route('/create'),
            'edit' => Pages\EditWrongkst::route('/{record}/edit'),
        ];
    }
}
