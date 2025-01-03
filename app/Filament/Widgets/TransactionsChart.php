<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionsChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?string $heading = 'Transaksi';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $transactions = Transaksi::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_harga) as total'),
                'vendor_id'
            )
            ->with('vendor:id,name')
            ->groupBy('date', 'vendor_id')
            ->orderBy('date')
            ->get();

        $vendors = $transactions->groupBy('vendor.name');

        return [
            'datasets' => $vendors->map(function ($vendorTransactions, $vendorName) {
                return [
                    'label' => $vendorName,
                    'data' => $vendorTransactions->pluck('total')->toArray(),
                    'borderColor' => '#' . substr(md5($vendorName), 0, 6),
                    'backgroundColor' => '#' . substr(md5($vendorName), 0, 6) . '20',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointStyle' => 'circle',
                    'pointRadius' => 5,
                    'pointHoverRadius' => 8,
                    'borderWidth' => 2,
                ];
            })->values()->toArray(),
            'labels' => $transactions->pluck('date')->unique()->toArray(),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'borderJoinStyle' => 'round',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
