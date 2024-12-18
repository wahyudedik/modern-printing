<?php

namespace App\Filament\App\Resources\SpesifikasiResource\Pages;

use App\Filament\App\Resources\SpesifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpesifikasi extends EditRecord
{
    protected static string $resource = SpesifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
