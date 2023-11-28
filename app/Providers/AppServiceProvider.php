<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;
use Filament\Support\Assets\Js;

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


      LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
          ->locales(['ar','en']); // also accepts a closure
      });
      Model::unguard();
    }
}
