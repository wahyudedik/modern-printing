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
    
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            Stat::make('Total Produk', Produk::count())
                ->description('Total produk dalam sistem')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Transaksi Hari Ini', Transaksi::whereDate('created_at', $today)->count())
                ->description('Transaksi hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([4, 5, 3, 7, 4, 5, 2, 6])
                ->icon('heroicon-o-banknotes'),

            Stat::make('Transaksi Bulanan', Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count())
                ->description('Total bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info')
                ->chart([3, 5, 7, 4, 6, 3, 5, 4])
                ->icon('heroicon-o-calendar'),
                
            Stat::make('Pendapatan Bulanan', function() use ($startOfMonth, $endOfMonth) {
                return 'Rp ' . number_format(
                    Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('status', 'success')
                        ->sum('total_harga'), 
                    0, 
                    ',', 
                    '.'
                );
            })
                ->description('Dari transaksi sukses')
                ->descriptionIcon('heroicon-m-currency-dollar') 
                ->color('success')
                ->chart([8, 3, 5, 7, 4, 3, 6, 5])
                ->icon('heroicon-o-currency-dollar'),
        ];
    }

    protected function getColumns(): int 
    {
        return 4;
    }
}
