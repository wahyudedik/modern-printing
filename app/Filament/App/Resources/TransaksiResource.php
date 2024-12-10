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
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(function () {
                                return 'TRX-' . now()->format('YmdHis') . '-' . Auth::user()->id;
                            })
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Kode pelanggan akan dibuat otomatis'),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('data_pelanggan')
                                    ->label('Data Pelanggan')
                                    ->relationship('pelanggan', 'nama')
                                    ->preload()
                                    ->required()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\Hidden::make('vendor_id')
                                            ->default(Filament::getTenant()->id),
                                        Forms\Components\TextInput::make('nama')
                                            ->required(),
                                        Forms\Components\TextInput::make('no_telp')
                                            ->tel()
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('alamat')
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->required()
                                            ->email(),
                                        Forms\Components\TextInput::make('kode')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(function () {
                                                return 'PLG-' . now()->format('YmdHis') . '-' . Auth::user()->id;
                                            })
                                            ->disabled()
                                            ->dehydrated()
                                            ->placeholder('Kode pelanggan akan dibuat otomatis'),

                                    ]),
                                Forms\Components\Select::make('data_produk')
                                    ->label('Data Produk')
                                    ->relationship('produk', 'nama_produk')
                                    ->multiple()
                                    ->preload()
                                    ->required()
                                    ->searchable()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $produkIds = $get('data_produk');
                                        if ($produkIds) {
                                            $produks = Produk::whereIn('id', $produkIds)->get();
                                            $set('minimal_qty', $produks->min('minimal_qty'));
                                            $set('total_harga', $produks->sum('total_harga'));
                                        }
                                    }),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('total_qty')
                                    ->label('Total Quantity')
                                    ->numeric()
                                    ->disabled()
                                    ->live(onBlur: true)
                                    ->required()
                                    ->rules([
                                        'required',
                                        'numeric',
                                        fn(Get $get): string => 'min:' . ($get('minimal_qty') ?? 0),
                                    ])
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $produkIds = $get('data_produk');
                                        $totalQty = $get('total_qty');

                                        if ($produkIds && $totalQty) {
                                            $produks = Produk::whereIn('id', $produkIds)->get();

                                            // Check minimal qty
                                            $minQty = $produks->min('minimal_qty');
                                            if ($totalQty < $minQty) {
                                                Notification::make()
                                                    ->warning()
                                                    ->title('Minimal Quantity Not Met')
                                                    ->body("Minimum quantity required is {$minQty}")
                                                    ->send();
                                                return;
                                            }

                                            // Calculate total
                                            $totalHarga = $produks->sum(function ($produk) use ($totalQty) {
                                                return (float) str_replace(['Rp', '.', ','], '', $produk->total_harga) * $totalQty;
                                            });

                                            $set('total_harga', $totalHarga);
                                        }
                                    }),

                                Forms\Components\TextInput::make('total_harga')
                                    ->label('Total Harga')
                                    ->disabled()
                                    ->numeric()
                                    ->dehydrated()
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('metode_pembayaran')
                                    ->options([
                                        'transfer' => 'Transfer',
                                        'cash' => 'Cash',
                                        'qris' => 'QRIS',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'success' => 'Success',
                                        'failed' => 'Failed',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ])->columns(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Produk')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Total Qty')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->badge()
                    ->icon('heroicon-o-credit-card')
                    ->colors([
                        'warning' => 'transfer',
                        'success' => 'cash',
                        'primary' => 'qris',
                    ]),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon('heroicon-o-check-circle')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->options([
                        'transfer' => 'Transfer',
                        'cash' => 'Cash',
                        'qris' => 'QRIS',
                    ])
                    ->label('Payment Method'),
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
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until'),
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
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
                                    'success' => 'Success',
                                    'failed' => 'Failed',
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update(['status' => $data['status']]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                // Open output buffer
                                $handle = fopen('php://output', 'w');

                                // Add CSV headers
                                fputcsv($handle, [
                                    'ID',
                                    'Vendor',
                                    'Customer Data',
                                    'Product Data',
                                    'Minimum Qty',
                                    'Total Qty',
                                    'Total Price',
                                    'Payment Method',
                                    'Status',
                                    'Created At'
                                ]);

                                // Add data rows
                                foreach ($records as $record) {
                                    fputcsv($handle, [
                                        $record->id,
                                        $record->vendor->name,
                                        json_encode($record->pelanggan),
                                        json_encode($record->produk),
                                        $record->minimal_qty,
                                        $record->total_qty,
                                        $record->total_harga,
                                        $record->metode_pembayaran,
                                        $record->status,
                                        $record->created_at
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
            //
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
