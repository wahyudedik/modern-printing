<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            
        ];
    }
}
