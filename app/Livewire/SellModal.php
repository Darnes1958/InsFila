<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Sell;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellModal extends Component implements HasForms

{
    use InteractsWithForms;
    protected static ?string $model =Sell::class;


    public

    public function form(Form $form): Form
    {
        return $form
            ->model(Sell::class)
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('order_date')
                            ->extraAttributes([
                                'wire:keydown.enter' => "\$dispatch('gotoitem', { test: 'customer_id' })",
                            ])
                            ->default(function (){
                                return now();
                            })
                            ->id('order_date')
                            ->autofocus()
                            ->live()
                            ->label('التاريخ')
                            ->columnSpan(2)
                            ->inlineLabel()
                            ->required(),
                        Select::make('customer_id')
                            ->label('الزبون')
                            ->options(Customer::where('id','!=',1)->pluck('name','id'))
                            ->searchable()
                            ->live()
                            ->required()
                            ->inlineLabel()
                            ->columnSpan(3)
                            ->extraAttributes([
                                'wire:change' => "\$dispatch('gotoitem', { test: 'place_id' })",
                                'wire:keydown.enter' => "\$dispatch('gotoitem', { test: 'place_id' })",
                            ])
                            ->id('customer_id'),
                        Select::make('place_id')
                            ->label('نقطة البيه')
                            ->relationship('Place','name')
                            ->live()
                            ->required()
                            ->inlineLabel()
                            ->columnSpan(2)
                            ->extraAttributes([
                                'wire:change' => "\$dispatch('gotoitem', { test: 'price_type_id' })",
                                'wire:keydown..enter' => "\$dispatch('goto', { test: 'price_type_id' })",
                            ])
                            ->id('place_id'),

                        Hidden::make('price_type_id')
                            ->default(3),
                        TextInput::make('tot')
                            ->label('إجمالي الفاتورة')
                            ->columnSpan(2)
                            ->inlineLabel()
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->columnSpan(4),
                Section::make()
                 ->schema([
                     TableRepeater::make('Sell_tran')
                         ->hiddenLabel()
                         ->required()
                         ->relationship('Sell_tran')
                         ->headers([
                             Header::make('رقم الصنف')
                                 ->width('50%'),
                             Header::make('الكمية')
                                 ->width('15%'),
                             Header::make('السعر')
                                 ->width('15%'),
                             Header::make('الاجمالي')
                                 ->width('20%'),

                         ])
                         ->schema([
                             Select::make('item_id')
                                 ->required()
                                 ->preload()
                                 ->searchable()
                              // ->relationship('Item','name')
                                    ->options(Item::all()->pluck('name','id'))
                                 ,

                             TextInput::make('q1')
                                 ->live(onBlur: true)
                                 ->extraInputAttributes(['tabindex' => 1])

                                 ->required(),
                             TextInput::make('p1')

                                 ->required() ,
                             TextInput::make('sub_tot')
                                 ->live(onBlur: true)
                                 ->extraInputAttributes(['tabindex' => 2])
                                 ->afterStateUpdated(function ($state,Set $set,Get $get){
                                     $set('price_input',round($state/$get('quant'),3));
                                     $set('price_cost',round($state/$get('quant'),3));
                                 })

                                 ->required(),

                         ])
                         ->defaultItems(0)
                         ->addable(function (){
                             return true;
                         })
                         ->afterStateUpdated(function ($state,Set $set,Get $get){
                             info($state);
                         })
                         ->live()
                         ->columnSpan('full')
                 ])
                 ->columnSpan(4),
            ])->columns(8);
    }
    public function render()
    {
        return view('livewire.sell-modal');
    }
}
