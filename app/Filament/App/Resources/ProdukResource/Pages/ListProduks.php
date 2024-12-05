<?php

namespace App\Filament\App\Resources\ProdukResource\Pages;

use App\Filament\App\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Produk')->icon('heroicon-o-plus'),
        ];
    }
}
