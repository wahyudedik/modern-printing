<?php

namespace App\Filament\App\Resources\TransaksiResource\Pages;

use App\Filament\App\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Transaksi')->icon('heroicon-o-plus'),
        ];
    }
}
