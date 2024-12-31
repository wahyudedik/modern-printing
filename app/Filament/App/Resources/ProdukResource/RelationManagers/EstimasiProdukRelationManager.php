<?php

namespace App\Filament\App\Resources\ProdukResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EstimasiProduk;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EstimasiProdukRelationManager extends RelationManager
{
    protected static string $relationship = 'estimasiProduk';

    public function form(Form $form): Form
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
                                Forms\Components\Hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Estimasi Produk')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->modalAlignment('center'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalAlignment('center'),
                ])
                    ->label('Aksi Terpilih')
                    ->icon('heroicon-m-chevron-down'),
            ]);
    }
}
