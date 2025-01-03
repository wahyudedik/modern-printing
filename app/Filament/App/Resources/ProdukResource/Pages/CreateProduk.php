<?php

namespace App\Filament\App\Resources\ProdukResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\ProdukResource;
use Illuminate\Support\Facades\Auth;

class CreateProduk extends CreateRecord
{
    protected static string $resource = ProdukResource::class;

    protected function afterCreate(): void
    {
        event(new VendorActivityEvent(
            'product_created',
            $this->record->toArray(),
            $this->record->vendor_id,
            Auth::id(),
            "Membuat Produk: {$this->record->nama_produk}"
        ));
    }
}
