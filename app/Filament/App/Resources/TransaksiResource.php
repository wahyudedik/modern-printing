<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TransaksiResource\Pages;
use App\Filament\App\Resources\TransaksiResource\RelationManagers;
use App\Filament\App\Resources\TransaksiResource\RelationManagers\TransaksiItemRelationManager;
use App\Filament\App\Resources\TransaksiResource\RelationManagers\TransaksiItemSpecificationsRelationManager;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $label = 'Transaksi';

    protected static ?string $pluralLabel = 'Transaksi';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $title = 'Transaksi';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'transaksi';

    protected static ?int $navigationSort = 2;

    // protected static ?string $navigationGroup = 'Bahan dan Alat';

    protected static ?string $navigationIcon = 'iconpark-transaction-o';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->description('Masukkan informasi transaksi dengan benar')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('kode')
                                    ->label('Kode Transaksi')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->readOnly()
                                    ->default('TRX-' . strtoupper(uniqid()))
                                    ->prefixIcon('heroicon-o-hashtag'),
                                Forms\Components\Select::make('user_id')
                                    ->label('User')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user'),
                                Forms\Components\Select::make('pelanggan_id')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-users'),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Section::make('Pembayaran')
                    ->description('Informasi pembayaran')
                    ->icon('heroicon-o-credit-card')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'quality_check' => 'Quality Check',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled'
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, $record, $set) {
                                $progressMap = [
                                    'pending' => 0,
                                    'processing' => 25,
                                    'quality_check' => 80,
                                    'completed' => 100,
                                    'cancelled' => 0
                                ];
                                $set('progress_percentage', $progressMap[$state]);
                                $record->updateOrderStatus($state);
                            })
                            ->default('pending')
                            ->required()
                            ->prefixIcon('heroicon-o-flag')
                            ->native(false),
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->prefixIcon('heroicon-o-currency-dollar'),
                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer Bank',
                                'ewallet' => 'E-Wallet'
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-credit-card')
                            ->native(false),
                        Forms\Components\TextInput::make('progress_percentage')
                            ->label('Progress (%)')
                            ->numeric()
                            ->readOnly()
                            ->default(0)
                            ->required()
                            ->prefixIcon('heroicon-o-chart-bar')
                            ->suffix('%'),
                    ])->columnSpan(['lg' => 1]),
                Forms\Components\Section::make('Informasi Tambahan')
                    ->description('Informasi estimasi dan tanggal')
                    ->icon('heroicon-o-clock')
                    ->collapsible()
                    ->schema([
                        Forms\Components\DateTimePicker::make('estimasi_selesai')
                            ->label('Estimasi Selesai')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar'),
                        Forms\Components\DatePicker::make('tanggal_dibuat')
                            ->label('Tanggal Dibuat')
                            ->required()
                            ->default(now())
                            ->prefixIcon('heroicon-o-calendar'),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3),
                    ])->columnSpan(['lg' => 1])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->description(fn(Transaksi $record): string => "Created by: {$record->user?->name}")
                    ->icon('heroicon-o-document-text')
                    ->tooltip('Click to copy transaction code')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'completed'
                    ])
                    ->icon('heroicon-o-check-circle')
                    ->description(fn(Transaksi $record): string => "Est. completion: {$record->estimasi_selesai}")
                    ->size('sm'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->description(fn(Transaksi $record): string => $record->user?->email ?? '-')
                    ->tooltip('User details')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-users')
                    ->description(fn(Transaksi $record): string => "Customer ID: {$record->pelanggan_id}")
                    ->tooltip('Customer details')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-storefront')
                    ->description(fn(Transaksi $record): string => "Vendor ID: {$record->vendor_id}")
                    ->tooltip('Vendor details')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->description(fn(Transaksi $record): string => "Payment: {$record->payment_method}")
                    ->tooltip('Total amount')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-clipboard-document-list')
                    ->tooltip('Transaction notes')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'completed'
                    ])
                    ->icon('heroicon-o-check-circle')
                    ->description(fn(Transaksi $record): string => "Est. completion: {$record->estimasi_selesai}")
                    ->sortable()
                    ->tooltip('Transaction status')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->size('sm')
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ])
                    ->label('Transaction Status'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })->columns(2)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->tooltip('View Details')
                        ->color('info')
                        ->modalWidth('5xl'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->tooltip('Edit Transaction')
                        ->color('warning')
                        ->modalWidth('3xl'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Delete Transaction')
                        ->color('danger')
                        ->modalAlignment('center'),
                ])->tooltip('Actions')
                    ->color('gray')
                    ->icon('heroicon-m-ellipsis-horizontal')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'pending' => 'Pending',
                                    'processing' => 'Processing',
                                    'quality_check' => 'Quality Check',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled'
                                ])
                                ->required()
                                ->native(false)
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update([
                                    'status' => $data['status'],
                                    'progress_percentage' => match ($data['status']) {
                                        'pending' => 0,
                                        'processing' => 25,
                                        'quality_check' => 80,
                                        'completed' => 100,
                                        'cancelled' => 0
                                    }
                                ]);
                                $record->updateOrderStatus($data['status']);
                            });
                        }),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                $handle = fopen('php://output', 'w');

                                // Headers
                                fputcsv($handle, [
                                    'Kode Transaksi',
                                    'Vendor',
                                    'User',
                                    'Customer',
                                    'Total Price',
                                    'Status',
                                    'Payment Method',
                                    'Progress',
                                    'Estimated Completion',
                                    'Created Date',
                                    'Items'
                                ]);

                                // Data rows
                                foreach ($records as $record) {
                                    $items = $record->transaksiItem->map(function ($item) {
                                        return [
                                            'product' => $item->produk->nama_produk ?? '-',
                                            'material' => $item->bahan->nama_bahan ?? '-',
                                            'quantity' => $item->kuantitas,
                                            'price' => $item->harga_satuan,
                                            'specs' => $item->spesifikasi
                                        ];
                                    })->toJson();

                                    fputcsv($handle, [
                                        $record->kode,
                                        $record->vendor->name,
                                        $record->user->name,
                                        $record->pelanggan->nama,
                                        $record->total_harga,
                                        $record->status,
                                        $record->payment_method,
                                        $record->progress_percentage . '%',
                                        $record->estimasi_selesai,
                                        $record->tanggal_dibuat,
                                        $items
                                    ]);
                                }

                                fclose($handle);
                            }, 'transactions.csv');
                        })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TransaksiItemRelationManager::class,
            TransaksiItemSpecificationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
