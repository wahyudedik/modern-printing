<?php

namespace App\Filament\Widgets;

use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Vendor;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardAdminStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Vendor', Vendor::count())
                ->description('Jumlah vendor terdaftar')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([2, 4, 6, 8, 10, 12, 14, 16])
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
