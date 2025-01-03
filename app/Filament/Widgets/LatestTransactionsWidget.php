<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestTransactionsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()->latest()
            )->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Order Code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Order code copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->tooltip('Vendor providing the service'),
                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Customer')
                    ->searchable()
                    ->tooltip('Customer details'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Price')
                    ->money('IDR')
                    ->sortable()
                    ->alignment('right')
                    ->color('success'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'primary' => 'quality_check'
                    ])
                    ->icon(fn(string $state): string => match ($state) {
                        'cancelled' => 'heroicon-o-x-circle',
                        'pending' => 'heroicon-o-clock',
                        'processing' => 'heroicon-o-arrow-path',
                        'completed' => 'heroicon-o-check-circle',
                        'quality_check' => 'heroicon-o-magnifying-glass',
                    }),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable()
                    ->color(fn(string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->alignment('center'),
                Tables\Columns\TextColumn::make('estimasi_selesai')
                    ->label('Est. Completion')
                    ->date()
                    ->sortable()
                    ->tooltip(fn(Transaksi $record): string => 'Created: ' . $record->created_at->format('M d, Y'))
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'quality_check' => 'Quality Check',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled'
                    ])
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->poll('30s');
    }
}
