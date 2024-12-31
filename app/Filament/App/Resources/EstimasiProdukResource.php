<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EstimasiProdukResource\Pages;
use App\Filament\App\Resources\EstimasiProdukResource\RelationManagers;
use App\Models\EstimasiProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstimasiProdukResource extends Resource
{
    protected static ?string $model = EstimasiProduk::class;
    protected static ?string $navigationLabel = 'Estimasi Produk';
    protected static ?string $modelLabel = 'Estimasi Produk';
    protected static ?string $pluralModelLabel = 'Estimasi Produk';
    protected static ?string $slug = 'waktu-pengerjaan';
    protected static ?int $navigationSort = 2; //29
    protected static bool $isScopedToTenant = true;
    protected static ?string $tenantOwnershipRelationshipName = 'vendor';
    protected static ?string $tenantRelationshipName = 'estimasiProduk';
    protected static ?string $navigationGroup = 'Bahan dan Alat';
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Estimasi Produk')
                    ->description('Masukkan informasi estimasi produk')
                    ->icon('heroicon-o-clock')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('produk_id')
                                    ->relationship('produk', 'nama_produk')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Produk')
                                    ->placeholder('Pilih produk')
                                    ->native(false)
                                    ->loadingMessage('Memuat produk...')
                                    ->noSearchResultsMessage('Tidak ada produk ditemukan')
                                    ->helperText('Pilih produk yang akan diestimasi'),
                                Forms\Components\Select::make('alat_id')
                                    ->relationship('alat', 'nama_alat')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Alat')
                                    ->placeholder('Pilih alat')
                                    ->native(false)
                                    ->loadingMessage('Memuat alat...')
                                    ->noSearchResultsMessage('Tidak ada alat ditemukan')
                                    ->helperText('Pilih alat yang digunakan'),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('waktu_persiapan')
                                    ->required()
                                    ->numeric()
                                    ->label('Waktu Persiapan')
                                    ->suffix('Menit')
                                    ->minValue(1)
                                    ->helperText('Masukkan estimasi waktu persiapan dalam menit')
                                    ->placeholder('0')
                                    ->inputMode('numeric'),
                                Forms\Components\TextInput::make('waktu_produksi_per_unit')
                                    ->required()
                                    ->numeric()
                                    ->label('Waktu Produksi Per Unit')
                                    ->suffix('Menit')
                                    ->minValue(1)
                                    ->helperText('Masukkan estimasi waktu produksi per unit dalam menit')
                                    ->placeholder('0')
                                    ->inputMode('numeric'),
                            ])->columns(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->description(fn(EstimasiProduk $record): string => strip_tags($record->produk->deskripsi) ?? '-')
                    ->copyable()
                    ->copyMessage('Nama produk disalin')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('alat.nama_alat')
                    ->label('Alat')
                    ->searchable()
                    ->sortable()
                    ->description(fn(EstimasiProduk $record): string => $record->alat->spesifikasi_alat ?? '-')
                    ->copyable()
                    ->copyMessage('Nama alat disalin')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('waktu_persiapan')
                    ->label('Waktu Persiapan')
                    ->sortable()
                    ->description('Dalam Menit')
                    ->numeric()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('waktu_produksi_per_unit')
                    ->label('Waktu Produksi Per Unit')
                    ->sortable()
                    ->description('Dalam Menit')
                    ->numeric()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('produk')
                    ->relationship('produk', 'nama_produk')
                    ->label('Produk')
                    ->placeholder('Semua Produk')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('alat')
                    ->relationship('alat', 'nama_alat')
                    ->label('Alat')
                    ->placeholder('Semua Alat')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['created_from'] && ! $data['created_until']) {
                            return null;
                        }

                        return 'Dibuat: ' . ($data['created_from'] ?? '...') . ' hingga ' . ($data['created_until'] ?? '...');
                    })
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
                    })->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->slideOver()
                        ->tooltip('Lihat Detail'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-m-pencil')
                        ->color('warning')
                        ->tooltip('Edit Data'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->tooltip('Hapus Data'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->modalHeading('Hapus Data Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus data yang dipilih? Data yang dihapus tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])->icon('heroicon-m-chevron-down'),
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
            'index' => Pages\ListEstimasiProduks::route('/'),
            'create' => Pages\CreateEstimasiProduk::route('/create'),
            'edit' => Pages\EditEstimasiProduk::route('/{record}/edit'),
        ];
    }
}
