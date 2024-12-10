<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Pos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Point of Sale';

    protected static ?string $title = 'Point of Sale';

    protected static string $view = 'filament.app.pages.pos';
}
