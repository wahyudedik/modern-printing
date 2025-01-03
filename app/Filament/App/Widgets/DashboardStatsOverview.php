<?php

namespace App\Filament\App\Widgets;

use App\Models\Produk;
use App\Models\Transaksi;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Filament\Support\Facades\FilamentIcon;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected ?string $heading = 'Statistik Toko';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            Stat::make('Total Produk', Produk::count())
                ->description('Total produk dalam sistem')
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
                ->icon('heroicon-o-shopping-cart')
                ->extraAttributes([
                    'class' => 'ring-2 ring-emerald-50 dark:ring-emerald-900 rounded-xl shadow-sm',
                ]),

            Stat::make('Transaksi Hari Ini', Transaksi::whereDate('created_at', $today)
                ->where('vendor_id', Filament::getTenant()->id)
                ->count())
                ->description('Transaksi hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([
                    Transaksi::whereDate('created_at', today()->subDays(6))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(5))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(4))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(3))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(2))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(1))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->count(),
                ])
                ->color(
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->count() >
                        Transaksi::whereDate('created_at', today()->subDay())->where('vendor_id', Filament::getTenant()->id)->count()
                        ? 'danger'
                        : 'success'
                )
                ->icon('heroicon-o-banknotes')
                ->extraAttributes([
                    'class' => 'ring-2 ring-amber-50 dark:ring-amber-900 rounded-xl shadow-sm',
                ]),

            Stat::make('Transaksi Bulanan', Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('vendor_id', Filament::getTenant()->id)
                ->count())
                ->description('Total bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([
                    Transaksi::whereDate('created_at', today()->subDays(6))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(5))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(4))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(3))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(2))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today()->subDays(1))->where('vendor_id', Filament::getTenant()->id)->count(),
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->count(),
                ])
                ->color(
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->count() >
                        Transaksi::whereDate('created_at', today()->subDay())->where('vendor_id', Filament::getTenant()->id)->count()
                        ? 'danger'
                        : 'success'
                )
                ->icon('heroicon-o-calendar')
                ->extraAttributes([
                    'class' => 'ring-2 ring-blue-50 dark:ring-blue-900 rounded-xl shadow-sm',
                ]),

            Stat::make('Pendapatan Bulanan', function () use ($startOfMonth, $endOfMonth) {
                return 'Rp ' . number_format(
                    Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('status', 'completed')
                        ->where('vendor_id', Filament::getTenant()->id)
                        ->sum('total_harga'),
                    0,
                    ',',
                    '.'
                );
            })
                ->description('Dari transaksi siap diambil')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([
                    Transaksi::whereDate('created_at', today()->subDays(6))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today()->subDays(5))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today()->subDays(4))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today()->subDays(3))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today()->subDays(2))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today()->subDays(1))->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga'),
                ])
                ->color(
                    Transaksi::whereDate('created_at', today())->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga') >
                        Transaksi::whereDate('created_at', today()->subDay())->where('vendor_id', Filament::getTenant()->id)->where('status', 'completed')->sum('total_harga')
                        ? 'danger'
                        : 'success'
                )
                ->icon('heroicon-o-currency-dollar')
                ->extraAttributes([
                    'class' => 'ring-2 ring-emerald-50 dark:ring-emerald-900 rounded-xl shadow-sm',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
