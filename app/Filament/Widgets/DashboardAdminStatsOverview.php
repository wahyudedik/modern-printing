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
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', \App\Models\User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-m-users')
                ->chart([
                    \App\Models\User::whereDate('created_at', today()->subDays(6))->count(),
                    \App\Models\User::whereDate('created_at', today()->subDays(5))->count(),
                    \App\Models\User::whereDate('created_at', today()->subDays(4))->count(),
                    \App\Models\User::whereDate('created_at', today()->subDays(3))->count(),
                    \App\Models\User::whereDate('created_at', today()->subDays(2))->count(),
                    \App\Models\User::whereDate('created_at', today()->subDays(1))->count(),
                    \App\Models\User::whereDate('created_at', today())->count(),
                ])
                ->color(
                    \App\Models\User::whereDate('created_at', today())->count() >
                        \App\Models\User::whereDate('created_at', today()->subDay())->count()
                        ? 'danger'
                        : 'success'
                )
                ->extraAttributes([
                    'class' => 'ring-2 ring-success-500/50 rounded-xl shadow-sm hover:shadow-lg transition duration-300',
                ]),

            Stat::make('Total Vendors', Vendor::count())
                ->description('Total registered vendors')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([
                    Vendor::whereDate('created_at', today()->subDays(6))->count(),
                    Vendor::whereDate('created_at', today()->subDays(5))->count(),
                    Vendor::whereDate('created_at', today()->subDays(4))->count(),
                    Vendor::whereDate('created_at', today()->subDays(3))->count(),
                    Vendor::whereDate('created_at', today()->subDays(2))->count(),
                    Vendor::whereDate('created_at', today()->subDays(1))->count(),
                    Vendor::whereDate('created_at', today())->count(),
                ])
                ->color(
                    Vendor::whereDate('created_at', today())->count() >
                        Vendor::whereDate('created_at', today()->subDay())->count()
                        ? 'danger'
                        : 'success'
                )
                ->extraAttributes([
                    'class' => 'ring-2 ring-primary-500/50 rounded-xl shadow-sm hover:shadow-lg transition duration-300',
                ]),

            Stat::make('Total Products', Produk::count())
                ->description('Total products across vendors')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([
                    Produk::whereDate('created_at', today()->subDays(6))->count(),
                    Produk::whereDate('created_at', today()->subDays(5))->count(),
                    Produk::whereDate('created_at', today()->subDays(4))->count(),
                    Produk::whereDate('created_at', today()->subDays(3))->count(),
                    Produk::whereDate('created_at', today()->subDays(2))->count(),
                    Produk::whereDate('created_at', today()->subDays(1))->count(),
                    Produk::whereDate('created_at', today())->count(),
                ])
                ->color(
                    Produk::whereDate('created_at', today())->count() >
                        Produk::whereDate('created_at', today()->subDay())->count()
                        ? 'danger'
                        : 'success'
                )
                ->extraAttributes([
                    'class' => 'ring-2 ring-warning-500/50 rounded-xl shadow-sm hover:shadow-lg transition duration-300',
                ]),

            Stat::make('Total Transactions', Transaksi::count())
                ->description('Total completed transactions')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([
                    Transaksi::whereDate('created_at', today()->subDays(6))->count(),
                    Transaksi::whereDate('created_at', today()->subDays(5))->count(),
                    Transaksi::whereDate('created_at', today()->subDays(4))->count(),
                    Transaksi::whereDate('created_at', today()->subDays(3))->count(),
                    Transaksi::whereDate('created_at', today()->subDays(2))->count(),
                    Transaksi::whereDate('created_at', today()->subDays(1))->count(),
                    Transaksi::whereDate('created_at', today())->count(),
                ])
                ->color(
                    Transaksi::whereDate('created_at', today())->count() >
                        Transaksi::whereDate('created_at', today()->subDay())->count()
                        ? 'danger'
                        : 'success'
                )
                ->extraAttributes([
                    'class' => 'ring-2 ring-info-500/50 rounded-xl shadow-sm hover:shadow-lg transition duration-300',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
