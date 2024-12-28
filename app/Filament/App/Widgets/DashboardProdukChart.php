<?php

namespace App\Filament\App\Widgets;

use App\Models\Produk;
use App\Models\TransaksiItem;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class DashboardProdukChart extends ChartWidget
{
    protected static ?string $heading = 'Produk Terpopuler';

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getTopProducts();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data['quantities'],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // Modern blue
                        'rgba(239, 68, 68, 0.8)',    // Modern red
                        'rgba(16, 185, 129, 0.8)',   // Modern green
                        'rgba(245, 158, 11, 0.8)',   // Modern amber
                        'rgba(139, 92, 246, 0.8)',   // Modern purple
                        'rgba(236, 72, 153, 0.8)',   // Modern pink
                        'rgba(14, 165, 233, 0.8)',   // Modern sky
                        'rgba(168, 85, 247, 0.8)',   // Modern violet
                        'rgba(251, 146, 60, 0.8)',   // Modern orange
                        'rgba(20, 184, 166, 0.8)',   // Modern teal
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)',
                        'rgb(168, 85, 247)',
                        'rgb(251, 146, 60)',
                        'rgb(20, 184, 166)',
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 8,
                    'hoverBackgroundColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(14, 165, 233, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(20, 184, 166, 1)',
                    ],
                ],
            ],
            'labels' => $data['products'],
        ];
    }

    protected function getTopProducts(): array
    {
        $topProducts = TransaksiItem::query()
            ->with('produk')
            ->select('produk_id')
            ->selectRaw('SUM(kuantitas) as total_sold')
            ->groupBy('produk_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return [
            'products' => $topProducts->map(fn($item) => $item->produk->nama_produk)->toArray(),
            'quantities' => $topProducts->pluck('total_sold')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'family' => "'Inter', sans-serif",
                            'size' => 12,
                        ],
                        'padding' => 16,
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'padding' => 12,
                    'titleFont' => [
                        'family' => "'Inter', sans-serif",
                        'size' => 14,
                    ],
                    'bodyFont' => [
                        'family' => "'Inter', sans-serif",
                        'size' => 12,
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'font' => [
                            'family' => "'Inter', sans-serif",
                            'size' => 11,
                        ],
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'family' => "'Inter', sans-serif",
                            'size' => 11,
                        ],
                    ],
                ],
            ],
        ];
    }
}
