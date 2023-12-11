<?php

namespace App\Providers;


use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

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
