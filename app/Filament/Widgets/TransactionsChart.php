<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $transactions = Transaksi::when(
            Filament::getTenant(),
            function ($query, $tenant) {
                return $query->where('vendor_id', $tenant->id);
            }
        )->whereBetween('created_at', [
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
                ];
            })->values()->toArray(),
            'labels' => $transactions->pluck('date')->unique()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
