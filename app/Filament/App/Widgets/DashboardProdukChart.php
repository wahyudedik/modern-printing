<?php

namespace App\Filament\App\Widgets;

use App\Models\Produk;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DashboardProdukChart extends ChartWidget
{
    protected static ?string $heading = 'Products Upload Statistics';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getMonthlyProducts();

        return [
            'datasets' => [
                [
                    'label' => 'Products Uploaded',
                    'data' => $data['products'],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getMonthlyProducts(): array
    {
        $months = collect();
        $products = collect();

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            $products->push(
                Produk::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        return [
            'labels' => $months->toArray(),
            'products' => $products->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
