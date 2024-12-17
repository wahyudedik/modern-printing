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
                    return response()->streamDownload(function () {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-transaksi', [
                            'transactions' => Transaksi::query()
                                ->whereBelongsTo(Filament::getTenant())
                                ->when(
                                    $this->fromDate,
                                    fn($query) =>
                                    $query->whereDate('created_at', '>=', $this->fromDate)
                                )
                                ->when(
                                    $this->untilDate,
                                    fn($query) =>
                                    $query->whereDate('created_at', '<=', $this->untilDate)
                                )
                                ->get(),
                            'fromDate' => $this->fromDate,
                            'untilDate' => $this->untilDate,
                        ]);

                        echo $pdf->output();
                    }, 'laporan-transaksi.pdf');
                })
                ->visible(fn() => $this->fromDate && $this->untilDate),
        ];
    }



    public function table(Table $table): Table
    {
        $query = Transaksi::query()
            ->whereBelongsTo(Filament::getTenant());

        // Apply date filters if they exist
        if ($this->fromDate || $this->untilDate) {
            $query->when($this->fromDate, function ($query) {
                return $query->whereDate('created_at', '>=', $this->fromDate);
            })
                ->when($this->untilDate, function ($query) {
                    return $query->whereDate('created_at', '<=', $this->untilDate);
                });
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                TextColumn::make('produk.nama_produk')
                    ->label('Produk')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                TextColumn::make('total_qty')
                    ->label('Total Qty')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->weight('bold'),
                TextColumn::make('metode_pembayaran')
                    ->badge()
                    ->icon('heroicon-o-credit-card')
                    ->colors([
                        'warning' => 'transfer',
                        'success' => 'cash',
                        'primary' => 'qris',
                    ]),
                TextColumn::make('status')
                    ->badge()
                    ->icon('heroicon-o-check-circle')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger' => 'failed',
                    ]),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true)
            ]);
    }
}
