<?php

namespace App\Livewire\widgets;

use App\Enums\Tar_type;
use App\Livewire\Traits\AksatTrait;
use App\Livewire\Traits\MainTrait;
use App\Models\Main;
use App\Models\Tran;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\On;


class TransWidget extends BaseWidget
{
    use MainTrait;
    use AksatTrait;
    protected static ?string $heading='';
    public $main_id;
    #[On('Take_Main_Id')]
    public function do($main_id)
    {
        $this->main_id=$main_id;
    }

    public function mount($main_id){
        $this->main_id=$main_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('لا توجد أقساط مخصومة')
            ->emptyStateDescription('لم يتم خصم أقساط بعد')
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([5,12,15,50,'all'])
            ->defaultSort('ser')
            ->query(function (Tran $tran){
                $tran=Tran::where('main_id',$this->main_id);
                return $tran;
            })

            ->recordUrl(null)
            ->columns([
                TextColumn::make('ser')
                    ->size(TextColumnSize::ExtraSmall)
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                Tables\Columns\TextColumn::make('kst_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label('ت.الاستحقاق'),
                Tables\Columns\TextColumn::make('ksm_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()

                    ->sortable()
                    ->label('ت.الخصم'),
                Tables\Columns\TextColumn::make('ksm')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الخصم'),
                Tables\Columns\TextColumn::make('ksm_type_id')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()
                    ->label('طريقة الدفع'),
                Tables\Columns\TextColumn::make('ksm_notes')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('ملاحظات'),
            ])
            ->actions([
                Tables\Actions\Action::make('tar')
                 ->label('ترجيع')
                 ->requiresConfirmation()

                ->action(function (Model $record){
                  $main=Main::find($this->main_id);
                    $main->tarkst()->create([
                        'main_id'=>$this->main_id,
                        'tar_date' => date('Y-m-d'),
                        'kst' => $record->ksm,
                        'tar_type' => Tar_type::من_قسط_مخصوم,
                        'haf_id' => $record->haf_id,
                        'user_id' => Auth::id(),
                        ]);
                    $record->delete();
                  $this->MainTarseed($this->main_id);
                  self::SortTrans2($this->main_id);

                })
            ]);
    }
}
