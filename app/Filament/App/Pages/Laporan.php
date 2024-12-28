<?php

namespace App\Filament\App\Pages;

use Closure;
use Filament\Pages\Page;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Laporan extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan';
    protected static ?string $slug = 'laporan';
    protected static string $view = 'filament.app.pages.laporan';
    protected static ?string $navigationGroup = 'Laporan';

    public $fromDate = null;
    public $untilDate = null;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('filter')
                ->label('Filter Date')
                ->icon('heroicon-m-funnel')
                ->form([
                    DatePicker::make('from')
                        ->label('From Date'),
                    DatePicker::make('until')
                        ->label('Until Date'),
                ])
                ->action(function (array $data): void {
                    $this->fromDate = $data['from'];
                    $this->untilDate = $data['until'];
                    $this->resetTable();
                }),

            \Filament\Actions\Action::make('print')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    $transactions = Transaksi::query()
                        ->with(['transaksiItem.produk', 'transaksiItem.bahan', 'pelanggan'])
                        ->whereBelongsTo(Filament::getTenant())
                        ->when(
                            $this->fromDate,
                            fn($query) => $query->whereDate('created_at', '>=', $this->fromDate)
                        )
                        ->when(
                            $this->untilDate,
                            fn($query) => $query->whereDate('created_at', '<=', $this->untilDate)
                        )
                        ->get();

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-transaksi', [
                        'transactions' => $transactions,
                        'fromDate' => $this->fromDate,
                        'untilDate' => $this->untilDate,
                        'totalRevenue' => $transactions->sum('total_harga'),
                        'totalTransactions' => $transactions->count(),
                        'tenant' => Filament::getTenant()
                    ]);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        "laporan-transaksi-{$this->fromDate}-to-{$this->untilDate}.pdf"
                    );
                })
                ->visible(fn() => $this->fromDate && $this->untilDate),
        ];
    }



    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->with(['transaksiItem.produk', 'transaksiItem.bahan', 'pelanggan'])
                    ->whereBelongsTo(Filament::getTenant())
                    ->when(
                        $this->fromDate,
                        fn($query) => $query->whereDate('created_at', '>=', $this->fromDate)
                    )
                    ->when(
                        $this->untilDate,
                        fn($query) => $query->whereDate('created_at', '<=', $this->untilDate)
                    )
            )
            ->columns([
                TextColumn::make('kode')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pelanggan.nama')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaksiItem.produk.nama_produk')
                    ->label('Products')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),

                TextColumn::make('transaksiItem.bahan.nama_bahan')
                    ->label('Materials')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),

                TextColumn::make('total_harga')
                    ->label('Revenue')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger' => 'failed'
                    ]),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->colors([
                        'warning' => 'transfer',
                        'success' => 'cash',
                        'primary' => 'qris'
                    ]),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}
