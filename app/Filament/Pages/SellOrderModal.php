<?php

namespace App\Filament\Pages;

use App\Enums\PlaceType;
use App\Models\Customer;
use App\Models\Sell;
use App\Models\Setting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SellOrderModal extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.sell-order-modal';
    public $sellData;

    protected function getForms(): array
    {
        return array_merge(parent::getForms(),[
            'sellForm'=> $this->makeForm()
                ->model(Sell::class)
                ->schema($this->getSellFormSchema())
                ->statePath('sellData'),
        ]);
    }

    protected function getContFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    DatePicker::make('order_date')
                        ->extraAttributes([
                            'wire:keydown.enter' => "\$dispatch('gotoitem', { test: 'customer_id' })",
                        ])
                        ->id('order_date')
                        ->autofocus()
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
                ->columns(8)
        ];
            }
}
