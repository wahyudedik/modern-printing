<?php

namespace App\Filament\App\Resources\AlatResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\AlatResource;

class CreateAlat extends CreateRecord
{
    protected static string $resource = AlatResource::class;

    protected function afterCreate(): void
    {
        event(new VendorActivityEvent(
            'equipment_created',
            $this->record->toArray(),
            $this->record->vendor_id,
            Auth::id(),
            "Membuat Alat: {$this->record->nama_alat}"
        ));
    }
}
