<?php

namespace App\Filament\App\Resources\AlatResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\AlatResource;

class EditAlat extends EditRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        event(new VendorActivityEvent(
            'equipment_updated',
            $this->record->getDirty(),
            $this->record->vendor_id,
            Auth::id(),
            "Update Alat: {$this->record->nama_alat}"
        ));
    }
}
