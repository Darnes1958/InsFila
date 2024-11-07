<?php
namespace App\Livewire\Traits;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;


trait PublicTrait{
    protected function getMainIdFromComponent(): TextInput
    {
        return TextInput::make('main_id')
            ->label('رقم العقد')
            ->required();
    }
    protected static function getMainSelectFromComponent(): Select
    {
        return Select::make('main_id')
            ->relationship('Main','Customer.name')
            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->Customer->name} {$record->sul}")
            ->searchable()
            ->preload()
            ->label('رقم العقد')
            ->required();
    }
    protected static function getKstFromComponent(): TextInput
    {
        return TextInput::make('kst')
            ->numeric()
            ->label('المبلغ')
            ->required();
    }
    protected static function getNoteFromComponent(): TextInput
    {
        return TextInput::make('notes')
            ->label('ملاحظات');
    }
    protected static function getDateFromComponent(): DatePicker
    {
        return DatePicker::make('over_date')
            ->label('التاريخ')
            ->default(now())
            ->required();
    }

}
