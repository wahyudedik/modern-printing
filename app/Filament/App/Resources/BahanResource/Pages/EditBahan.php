<?php

namespace App\Filament\App\Resources\BahanResource\Pages;

use Filament\Actions;
use App\Events\VendorActivityEvent;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\BahanResource;

class EditBahan extends EditRecord
{
    protected static string $resource = BahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        event(new VendorActivityEvent(
            'material_updated',
            $this->record->getDirty(),
            $this->record->vendor_id,
            Auth::id(),
            "Update Bahan: {$this->record->nama_bahan}"
        ));
    }
}
