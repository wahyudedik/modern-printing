<?php

namespace App\Filament\App\Resources\TransaksiResource\Pages;

use App\Filament\App\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksi extends EditRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('transaksi.print', $record))
                ->openUrlInNewTab(),
        ];
    }
}
