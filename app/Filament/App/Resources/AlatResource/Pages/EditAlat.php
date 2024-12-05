<?php

namespace App\Filament\App\Resources\AlatResource\Pages;

use App\Filament\App\Resources\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlat extends EditRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
