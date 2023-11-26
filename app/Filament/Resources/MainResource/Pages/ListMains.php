<?php

namespace App\Filament\Resources\MainResource\Pages;

use App\Filament\Resources\MainResource;
use App\Models\Customer;
use App\Models\Main;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMains extends ListRecords
{
    protected static string $resource = MainResource::class;

  public function getTabs(): array
  {
    return [
      'all' => Tab::make()->icon('heroicon-m-user-group')
        ->badge(Main::query()->where('user_id', 1)->count()),
      'active' => Tab::make()
        ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', 1)),
      'inactive' => Tab::make()
        ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', 2)),
    ];
  }

#Cust
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
