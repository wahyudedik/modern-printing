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
use Filament\Tables\Columns\Summarizers\Sum;
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
                        ->with([
                            'transaksiItem.produk',
                            'transaksiItem.transaksiItemSpecifications.spesifikasiProduk.spesifikasi',
                            'transaksiItem.transaksiItemSpecifications.bahan',
                            'pelanggan',
                            'vendor'
                        ])
                        ->where('status', 'completed')
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
                    ->with([
                        'transaksiItem.produk',
                        'transaksiItem.transaksiItemSpecifications.spesifikasiProduk.spesifikasi',
                        'transaksiItem.transaksiItemSpecifications.bahan',
                        'pelanggan',
                        'vendor'
                    ])
                    ->where('status', 'completed')
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

                TextColumn::make('transaksiItem')
                    ->label('Materials & Specifications')
                    ->state(function ($record) {
                        // Get unique products first
                        $uniqueProducts = [];
                        foreach ($record->transaksiItem as $item) {
                            $key = $item->produk_id;
                            if (!isset($uniqueProducts[$key])) {
                                $uniqueProducts[$key] = $item;
                            }
                        }
                        // Format the output for unique products only
                        $output = collect($uniqueProducts)->map(function ($item) {
                            $productName = $item->produk->nama_produk;
                            $specs = $item->transaksiItemSpecifications->map(function ($spec) use ($item) {
                                $value = $spec->input_type === 'select'
                                    ? "<span class='font-medium text-primary-600'>{$spec->bahan->nama_bahan}</span>"
                                    : "<span class='font-medium text-primary-600'>{$spec->value} {$spec->spesifikasiProduk->spesifikasi->satuan}</span>";

                                return "<div class='flex items-center gap-1'>
                                            <span class='text-gray-600'>{$spec->spesifikasiProduk->spesifikasi->nama_spesifikasi}:</span>
                                            {$value}
                                            <span class='text-gray-500 text-sm'>(x{$item->kuantitas})</span>
                                        </div>";
                            })->join("\n");

                            return "<div class='space-y-2'>
                                        <div class='font-semibold text-gray-900'>{$productName} :</div>
                                        <div class='pl-4 space-y-1'>{$specs}</div>
                                    </div>";
                        })->join("\n<hr class='my-2 border-gray-200'>\n");
                        return $output;
                    })
                    ->listWithLineBreaks()
                    ->searchable()
                    ->html(),

                TextColumn::make('transaksiItem.kuantitas')
                    ->label('Product Quantity')
                    ->summarize(
                        Sum::make()
                            ->label('Total Quantity')
                    )
                    ->listWithLineBreaks()
                    ->alignCenter()
                    ->sortable()
                    ->size('sm')
                    ->color('primary')
                    ->tooltip('Total quantity of items ordered')
                    ->formatStateUsing(fn($state) => number_format($state))
                    ->icon('heroicon-o-shopping-cart'),

                TextColumn::make('total_harga')
                    ->label('Revenue')
                    ->money('idr')
                    ->summarize(
                        Sum::make()
                            ->label('Total Revenue')
                    )
                    ->sortable(),

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
