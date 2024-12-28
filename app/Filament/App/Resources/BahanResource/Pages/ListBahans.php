<?php

namespace App\Filament\App\Resources\BahanResource\Pages;

use App\Filament\App\Resources\BahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBahans extends ListRecords
{
    protected static string $resource = BahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Bahan')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->tooltip('Klik untuk menambah bahan baru')
                ->modalWidth('lg')
                ->slideOver(),
        ];
    }
}
