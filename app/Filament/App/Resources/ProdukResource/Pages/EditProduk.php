<?php

namespace App\Filament\App\Resources\ProdukResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\ProdukResource;

class EditProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        event(new VendorActivityEvent(
            'product_updated',
            $this->record->getDirty(),
            $this->record->vendor_id,
            Auth::id(),
            "Update Produk: {$this->record->nama_produk}"
        ));
    }
}
