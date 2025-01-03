<?php

namespace App\Filament\App\Resources\TransaksiResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\TransaksiResource;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function afterCreate(): void
    {
        event(new VendorActivityEvent(
            'transaction_created',
            $this->record->toArray(),
            $this->record->vendor_id,
            Auth::id(),
            "Transaksi Baru: {$this->record->kode}"
        ));
    }
}
