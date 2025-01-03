<?php

namespace App\Filament\App\Resources\TransaksiResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\TransaksiResource;

class EditTransaksi extends EditRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ]; 
    }

    protected function afterSave(): void
    {
        event(new VendorActivityEvent(
            'transaction_updated',
            $this->record->getDirty(),
            $this->record->vendor_id,
            Auth::id(),
            "Update Transaksi: {$this->record->kode}"
        ));
    }
}
