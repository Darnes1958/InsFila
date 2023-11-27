<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;


class Reports extends Page
{


    public static $label = 'Custom Navigation Label';

    public static ?string $slug = 'custom-url-slug';

    public static ?string $title = 'تقرير عن مصرف';

    protected static ?string $navigationGroup='تقارير';




    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static function getPluralModelLabel(): string
    {
        return __('تقرير');
    }

    protected static string $view = 'filament.pages.reports';
}
