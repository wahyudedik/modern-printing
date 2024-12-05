<?php

namespace App\Filament\App\Resources\AlatResource\Pages;

use App\Filament\App\Resources\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlats extends ListRecords
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Alat')->icon('heroicon-o-plus'),
        ];
    }
}
