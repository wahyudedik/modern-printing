<?php

namespace App\Filament\App\Resources\EstimasiProdukResource\Pages;

use App\Filament\App\Resources\EstimasiProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstimasiProduks extends ListRecords
{
    protected static string $resource = EstimasiProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
