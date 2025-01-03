<?php

namespace App\Filament\App\Resources\BahanResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\BahanResource;
use Illuminate\Support\Facades\Auth;

class CreateBahan extends CreateRecord
{
    protected static string $resource = BahanResource::class;

    protected function afterCreate(): void
    {
        event(new VendorActivityEvent(
            'material_created',
            $this->record->toArray(),
            $this->record->vendor_id,
            Auth::id(),
            "Membuat Bahan: {$this->record->nama_bahan}"
        ));
    }
}
