<?php

namespace App\Filament\Resources\VendorActivityResource\Pages;

use App\Filament\Resources\VendorActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVendorActivities extends ManageRecords
{
    protected static string $resource = VendorActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
