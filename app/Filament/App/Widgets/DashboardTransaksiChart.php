<?php

namespace App\Filament\App\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DashboardTransaksiChart extends ChartWidget
{
    protected static ?string $heading = 'Transactions Overview';

    protected static ?int $sort = 2;

    protected function getData(): array
    { 
        $data = $this->getDailyTransactions();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Transactions',
                    'data' => $data['transactions'],
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
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
            $days->push($date->format('D'));

            $transactions->push(
                Transaksi::whereDate('created_at', $date)->count()
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
}
