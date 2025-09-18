<?php
namespace App\Livewire\Traits;

use App\Models\OurCompany;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


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

    public static function ret_spatie_header(){
        return       $headers = [
            'Content-Type' => 'application/pdf',
        ];

    }
    public static function ret_spatie($res,$blade,$arr=[])
    {
        if(!File::exists(Auth::user()->company)) {
            File::makeDirectory(Auth::user()->company);
        }
        $cus=OurCompany::where('Company',Auth::user()->company)->first();
        \Spatie\LaravelPdf\Facades\Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr,'cus'=>$cus])
            ->save(Auth::user()->company.'/invoice-2023-04-10.pdf');
        Log::info('that is it');
        return public_path().'/'.Auth::user()->company.'/invoice-2023-04-10.pdf';

    }
    public static function ret_spatie_land($res,$blade,$arr=[])
    {
        \Spatie\LaravelPdf\Facades\Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr])
            ->landscape()
            ->save(Auth::user()->company.'/invoice-2023-04-10.pdf');
        return public_path().'/'.Auth::user()->company.'/invoice-2023-04-10.pdf';

    }


}
