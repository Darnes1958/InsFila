<?php

namespace App\Providers;


use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

use Filament\Tables\Columns\Column;
use Illuminate\View\View;

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



        FilamentView::registerRenderHook(
            'panels::page.end',
            fn (): View => view('analytics'),
            scopes: [
                \App\Filament\Resources\MainResource::class,


            ]
        );
        FilamentAsset::register([
            \Filament\Support\Assets\Js::make('example-external-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'),

        ]);

      FilamentColor::register([
        'Fuchsia' =>  Color::Fuchsia,
        'green' =>  Color::Green,
        'blue' =>  Color::Blue,
        'gray' =>  Color::Gray,
      ]);
        DB::listen(function ($query) {
          // info($query->sql);
            // $query->sql
            // $query->bindings
            // $query->time
        });

      LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
          ->locales(['ar','en']); // also accepts a closure
      });
      Model::unguard();
    }
}
