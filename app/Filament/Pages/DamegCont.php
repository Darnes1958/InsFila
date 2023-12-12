<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DamegCont extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dameg-cont';

    protected ?string $heading = '';
    public function getBreadcrumbs(): array
    {
        return [""];
    }

    protected static ?string $navigationLabel='ضم عقد';
    protected static ?int $navigationSort = 3;
}
