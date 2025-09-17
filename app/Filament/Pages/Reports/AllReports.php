<?php

namespace App\Filament\Pages\Reports;

use App\Livewire\Traits\MainTrait;
use App\Livewire\Traits\PublicTrait;
use App\Models\Bank;
use App\Models\Main;
use App\Models\OurCompany;
use App\Models\Taj;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use Filament\Actions;

class AllReports extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;
    use MainTrait;
    use PublicTrait;
    protected ?string $heading = '';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->can('تقرير عن مصرف');
    }

    public static ?string $title = 'تقارير عن مصرف';

    protected static ?string $navigationGroup='تقارير';
    protected static ?int $navigationSort=6;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports.all-reports';

    public $bank_id;

    public $is_show=false;

    public $query;
    public $rep_name='All';
    public $Date1;
    public $Date2;
    public $Baky=5;
    public $BakyLabel='الباقي';

    public $sul;
    public $pay;
    public $raseed;
    public $notPay=false;

    public array $data_list= [
    'calc_columns' => [
        'acc',
        'sul',
        'pay',
        'raseed',
    ],
        ];
protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('prinitem')
            ->label('طباعة')
            ->icon('heroicon-s-printer')
            ->color('success')
            ->action(function (){
                $RepDate=date('Y-m-d');
                $cus=OurCompany::where('Company',Auth::user()->company)->first();

                \Spatie\LaravelPdf\Facades\Pdf::view('PrnView.pdf-all',
                    ['res'=>$this->getTableQueryForExport()->get(),
                        'cus'=>$cus,'RepDate'=>$RepDate,
                    ])
                    ->headerHtml('<div>My header</div>')
                    ->footerView('PrnView.footer')
                    ->margins(10, 10, 40, 10, Unit::Pixel)
                    ->save(Auth::user()->company.'/invoice-2023-04-10.pdf');
                $file= public_path().'/'.Auth::user()->company.'/invoice-2023-04-10.pdf';

                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return Response::download($file, 'filename.pdf', $headers);
            }),
    ];
}

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('bank_id')
                    ->columnSpan(2)
                    ->options(Taj::all()->pluck('TajName', 'id')->toArray())
                    ->searchable()
                    ->hiddenLabel()
                    ->prefix('المصرف التجميعي')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->bank_id=$state;
                    }),
                Select::make('rep_name')
                    ->columnSpan(2)

                    ->hiddenLabel()
                    ->prefix('التقرير')
                    ->default('All')
                    ->reactive()

                    ->options([
                        'All' => 'كشف بالأسماء',
                        'Mosdada' => 'المسددة',
                        'NotMosdada' => 'لم تسدد بعد',
                        'Motakra' => 'المتأخرة',
                        'Mohasla' => 'المحصلة',
                        'Not_Mohasla' => 'الغير محصلة',
                    ])
                    ->afterStateUpdated(function (callable $get){
                        if ($get('rep_name')=='Mosdada') {$this->Baky=5;$this->BakyLabel='الباقي';}
                        if ($get('rep_name')=='Motakra') {$this->Baky=1;$this->BakyLabel='عدد الأقساط المتأخرة';}
                    }),

                TextInput::make('Baky')
                    ->hiddenLabel()
                    ->prefix(function (){
                        return $this->BakyLabel;
                    })
                    ->reactive()
                    ->numeric()
                    ->visible(fn (Get $get): bool => $get('rep_name')=='Mosdada' || $get('rep_name')=='Motakra'),
                Checkbox::make('notPay')
                 ->live()
                 ->visible(fn(Get $get): bool => $get('rep_name')=='Motakra')
                 ->label('لم تسدد بعد'),

                DatePicker::make('Date1')
                    ->inlineLabel()
                    ->label('من')
                    ->reactive()
                    ->visible(fn (Get $get): bool => $get('rep_name')=='Mohasla' || $get('rep_name')=='Not_Mohasla'),
                DatePicker::make('Date2')
                    ->inlineLabel()
                    ->label('إلي')
                    ->reactive()
                    ->visible(fn (Get $get): bool => $get('rep_name')=='Mohasla' || $get('rep_name')=='Not_Mohasla'),
                \Filament\Forms\Components\Actions::make([

                Action::make('names')
                 ->label('طباعة')
                 ->icon('heroicon-o-printer')
                 ->url( function ():string {
                    if ($this->rep_name=='All') return route('pdfnames',['bank_id'=>$this->bank_id]);
                    if ($this->rep_name=='Mosdada') return route('pdfmosdadabank',['Baky'=>$this->Baky,'bank_id'=>$this->bank_id]);
                     if ($this->rep_name=='NotMosdada') return route('pdfnotmosdadabank',['bank_id'=>$this->bank_id]);
                    if ($this->rep_name=='Motakra') return route('pdfmotakrabank',['Baky'=>$this->Baky,'bank_id'=>$this->bank_id,'notPay'=>$this->notPay]);
                    if ($this->rep_name=='Mohasla') return route('pdfmohasla',['bank_id'=>$this->bank_id,'Date1'=>$this->Date1,'Date2'=>$this->Date2]);
                    if ($this->rep_name=='Not_Mohasla') return route('pdfnotmohasla',['bank_id'=>$this->bank_id,'Date1'=>$this->Date1,'Date2'=>$this->Date2]);
                 })

               ])

            ])
            ->extraAttributes(['class'=>'p-y-2 gap-y-2'])
            ->columns(7);
    }



    public function table(Table $table):Table
    {
        return $table
            ->pluralModelLabel('العقود')
            ->query(function (Main $main)  {
                    $main=Main::where('taj_id',$this->bank_id)
                        ->when($this->rep_name=='Mosdada' , function ($q) {
                            $q->where('raseed','<=',$this->Baky); })
                        ->when($this->rep_name=='NotMosdada' , function ($q) {
                            $q->where('pay',0); })
                        ->when($this->rep_name=='Motakra' , function ($q) {
                            $q->where('late','>=',$this->Baky); })
                        ->when($this->rep_name=='Motakra' && $this->notPay, function ($q) {
                            $q->where('pay',0); });

                $this->sul=number_format($main->sum('sul'),0, '', ',')  ;
                $this->pay=number_format($main->sum('pay'),0, '', ',')  ;
                $this->raseed=number_format($main->sum('raseed'),0, '', ',')  ;
                return  $main;
            })
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('رقم العقد'),
                TextColumn::make('acc')
                    ->sortable()
                    ->searchable()
                    ->label('رقم الحساب'),
                TextColumn::make('Customer.name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                TextColumn::make('sul')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('اجمالي العقد'),
                TextColumn::make('kst')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('القسط'),
                TextColumn::make('pay')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('المسدد'),
                TextColumn::make('raseed')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('الرصيد'),
                TextColumn::make('Late')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))

                    ->label('متأخرة')
                    ->visible(fn (Get $get): bool =>$this->rep_name =='Motakra')
                    ->color('danger'),
                TextColumn::make('sul_begin')
                    ->label('تاريخ العقد')
                    ->visible(fn (Get $get): bool =>$this->rep_name =='Motakra')
                    ->color('info'),
                TextColumn::make('LastKsm')
                    ->label('ت.أخر قسط')
                    ->visible(fn (Get $get): bool =>$this->rep_name =='Motakra')
                    ->color('danger'),
            ])
           ;
    }

    public function mount(){

        $this->Date1=date('Y-m-d');
        $this->Date2=date('Y-m-d');
        $this->LateChk();
        $this->bank_id=Taj::min('id');
        //$this->form->fill(['By'=>1,]);
    }

}
