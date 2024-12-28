<?php

namespace App\Filament\App\Resources\PelangganResource\Pages;

use App\Filament\App\Resources\PelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelanggans extends ListRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pelanggan')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->tooltip('Klik untuk menambah pelanggan baru')
                ->modalWidth('lg')
                ->slideOver(),
        ];
    }
}
