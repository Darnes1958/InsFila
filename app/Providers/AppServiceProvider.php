<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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
