<?php

namespace App\Filament\Resources\VendorUserResource\Pages;

use App\Filament\Resources\VendorUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVendorUsers extends ManageRecords
{
    protected static string $resource = VendorUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
