<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HafithaResource\Pages;
use App\Filament\Resources\HafithaResource\RelationManagers;
use App\Livewire\Traits\AksatTrait;
use App\Models\Hafitha;
use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tran;
use App\Models\Trans_arc;
use App\Models\Wrongkst;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HafithaResource extends Resource
{
    use AksatTrait;
    protected static ?string $model = Hafitha::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='حوافظ';
    protected static ?int $navigationSort=9;


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
                TextColumn::make('id')->label('الرقم الألي')->sortable()->searchable(),
                TextColumn::make('Taj.TajName')->label('رقم الحساب')->searchable()->sortable(),
                TextColumn::make('from_date')->label('تاريخ بداية الحافظ')->searchable()->sortable(),
                TextColumn::make('to_date')->label('تاريخ نهاية الحافظة')->searchable()->sortable(),
                TextColumn::make('tot')->label('الاجمالي')->searchable()->sortable(),
                TextColumn::make('morahel')->label('المرحل')->sortable(),
                TextColumn::make('over_kst')->label('الفائض')->sortable(),
                TextColumn::make('over_kst_arc')->label('الفائض من الارشيف')->sortable(),
                TextColumn::make('half')->label('الجزئي')->sortable(),
                TextColumn::make('wrong_kst')->label('بالخطأ')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Delete Hafitha')
                    ->color('danger')
                    ->action(function ($record){
                                Tran::where('haf_id',$record->id)->delete();
                                Trans_arc::where('haf_id',$record->id)->delete();
                                Overkst::where('haf_id',$record->id)->delete();
                                Wrongkst::where('haf_id',$record->id)->delete();

                                $mains=Main::where('taj_id',$record->taj_id)->get();
                                foreach ($mains as $main){
                                    self::MainTarseed2($main->id);
                                }

                                $record->delete();

                                Notification::make()
                                    ->title('تم حذف الحافظة')
                                    ->success()
                                    ->send();




                    })
                    ->requiresConfirmation()
                    ->visible(Auth::id()==1)

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
            'index' => Pages\ListHafithas::route('/'),
            'create' => Pages\CreateHafitha::route('/create'),
            'edit' => Pages\EditHafitha::route('/{record}/edit'),
        ];
    }
}
