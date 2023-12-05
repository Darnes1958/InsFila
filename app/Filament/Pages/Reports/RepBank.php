<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;

class RepBank extends Page
{
    public static ?string $title = 'تقارير عن أقساط';

    protected static ?string $navigationGroup='تقارير';




    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static function getPluralModelLabel(): string
    {
        return __('تقرير');
    }

    protected static string $view = 'filament.pages.reports.rep-bank';
}
