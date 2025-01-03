<?php

namespace App\Filament\App\Widgets;

use App\Models\Transaksi;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class DashboardTransaksiChart extends ChartWidget
{
    protected static ?string $heading = 'Ringkasan Transaksi';

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = $this->getDailyTransactions();

        return [
            'datasets' => [
                [
                    'label' => 'Transaksi Harian',
                    'data' => $data['transactions'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => '#36A2EB',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => '#36A2EB',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => '#36A2EB',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getDailyTransactions(): array
    {
        $days = collect();
        $transactions = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days->push($date->format('D, d M'));

            $transactions->push(
                Transaksi::whereDate('created_at', $date)
                    ->where('vendor_id', Filament::getTenant()->id)
                    ->count()
            );
        }

        return [
            'labels' => $days->toArray(),
            'transactions' => $transactions->toArray(),
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
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
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
