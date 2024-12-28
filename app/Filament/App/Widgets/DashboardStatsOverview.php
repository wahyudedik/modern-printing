<?php

namespace App\Filament\App\Widgets;

use App\Models\Produk;
use App\Models\Transaksi;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Filament\Support\Facades\FilamentIcon;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

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
                ->color(Color::Emerald)
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->icon('heroicon-o-shopping-cart')
                ->extraAttributes([
                    'class' => 'ring-2 ring-emerald-50 dark:ring-emerald-900 rounded-xl shadow-sm',
                ]),

            Stat::make('Transaksi Hari Ini', Transaksi::whereDate('created_at', $today)->count())
                ->description('Transaksi hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color(Color::Amber)
                ->chart([4, 5, 3, 7, 4, 5, 2, 6])
                ->icon('heroicon-o-banknotes')
                ->extraAttributes([
                    'class' => 'ring-2 ring-amber-50 dark:ring-amber-900 rounded-xl shadow-sm',
                ]),

            Stat::make('Transaksi Bulanan', Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count())
                ->description('Total bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color(Color::Blue)
                ->chart([3, 5, 7, 4, 6, 3, 5, 4])
                ->icon('heroicon-o-calendar')
                ->extraAttributes([
                    'class' => 'ring-2 ring-blue-50 dark:ring-blue-900 rounded-xl shadow-sm',
                ]),
                
            Stat::make('Pendapatan Bulanan', function() use ($startOfMonth, $endOfMonth) {
                return 'Rp ' . number_format(
                    Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('status', 'completed')
                        ->sum('total_harga'), 
                    0, 
                    ',', 
                    '.'
                );
            })
                ->description('Dari transaksi siap diambil')
                ->descriptionIcon('heroicon-m-currency-dollar') 
                ->color(Color::Emerald)
                ->chart([8, 3, 5, 7, 4, 3, 6, 5])
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
