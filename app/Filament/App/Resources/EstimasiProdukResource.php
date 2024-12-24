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
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('produk_id')
                                    ->relationship('produk', 'nama_produk')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Produk'),
                                Forms\Components\Select::make('alat_id')
                                    ->relationship('alat', 'nama_alat')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Alat'),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('waktu_persiapan')
                                    ->required()
                                    ->numeric()
                                    ->label('Waktu Persiapan (Menit)')
                                    ->minValue(1),
                                Forms\Components\TextInput::make('waktu_produksi_per_unit')
                                    ->required()
                                    ->numeric()
                                    ->label('Waktu Produksi Per Unit (Menit)')
                                    ->minValue(1),
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
                    ->description(fn(EstimasiProduk $record): string => strip_tags($record->produk->deskripsi) ?? '-'),
                Tables\Columns\TextColumn::make('alat.nama_alat')
                    ->label('Alat')
                    ->searchable()
                    ->sortable()
                    ->description(fn(EstimasiProduk $record): string => $record->alat->spesifikasi ?? '-'),
                Tables\Columns\TextColumn::make('waktu_persiapan')
                    ->label('Waktu Persiapan')
                    ->sortable()
                    ->description('Dalam Menit')
                    ->numeric(),
                Tables\Columns\TextColumn::make('waktu_produksi_per_unit')
                    ->label('Waktu Produksi Per Unit')
                    ->sortable()
                    ->description('Dalam Menit')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEstimasiProduks::route('/'),
            'create' => Pages\CreateEstimasiProduk::route('/create'),
            'edit' => Pages\EditEstimasiProduk::route('/{record}/edit'),
        ];
    }
}
