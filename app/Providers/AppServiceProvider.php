<?php

namespace App\Providers;


use App\Filament\Pages\KsmKst;
use App\Filament\Pages\newCont;
use App\Models\GlobalSetting;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

use Filament\Tables\Columns\Column;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
   //     Notifications::alignment(Alignment::Center);
     //   Notifications::verticalAlignment(VerticalAlignment::Center);
        Pdf::default()
            ->footerView('PrnView.footer')
            ->withBrowsershot(function (Browsershot $shot) {
                $shot->noSandbox()
                    ->setChromePath(GlobalSetting::first()->LiteExePath);
            })
            ->margins(10, 10, 20, 10, );
        Table::$defaultNumberLocale = 'nl';
        FilamentView::registerRenderHook(
            'panels::page.end',
            fn (): View => view('analytics'),
            scopes: [
                \App\Filament\Resources\MainResource::class,
                newCont::class,
                KsmKst::class,
            ]
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('@livewire(\'top-bar\')'),
        );
        FilamentAsset::register([
            \Filament\Support\Assets\Js::make('example-external-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'),
        ]);

      FilamentColor::register([
        'Fuchsia' =>  Color::Fuchsia,
        'green' =>  Color::Green,
        'blue' =>  Color::Blue,
        'gray' =>  Color::Gray,
        'yellow' =>  Color::Yellow,
        'lime' =>  Color::Lime,
      ]);
        Filament::registerNavigationGroups([
            'تقارير',
            'اعدادات',
            'Setting',
        ]);
        DB::listen(function ($query) {
          // info($query->sql);
            // $query->sql
            // $query->bindings
            // $query->time
        });


      Model::unguard();
    }
}
