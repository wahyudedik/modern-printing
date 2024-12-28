<?php

namespace App\Filament\App\Resources\SpesifikasiResource\Pages;

use App\Filament\App\Resources\SpesifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpesifikasis extends ListRecords
{
    protected static string $resource = SpesifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus')->label('Tambah Spesifikasi'),
        ];
    }
}
