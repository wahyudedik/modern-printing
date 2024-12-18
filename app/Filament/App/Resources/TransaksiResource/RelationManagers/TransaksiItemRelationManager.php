<?php

namespace App\Filament\App\Resources\TransaksiResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TransaksiItemRelationManager extends RelationManager
{
    protected static string $relationship = 'transaksiItem';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Item Details')
                    ->schema([
                        Forms\Components\Hidden::make('vendor_id')
                            ->default(Filament::getTenant()->id),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('produk_id')
                                    ->relationship('produk', 'nama_produk')
                                    ->required()
                                    ->searchable()
                                    ->label('Product'),
                                Forms\Components\Select::make('bahan_id')
                                    ->relationship('bahan', 'nama_bahan')
                                    ->required()
                                    ->searchable()
                                    ->label('Material'),
                            ])->columns(2),

                        Forms\Components\Section::make('Pricing & Quantity')
                            ->schema([
                                Forms\Components\TextInput::make('kuantitas')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->label('Quantity'),

                                Forms\Components\TextInput::make('harga_satuan')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->label('Unit Price'),
                            ])->columns(2),

                        Forms\Components\Section::make('Specifications')
                            ->schema([
                                Forms\Components\Select::make('spesifikasi')
                                    ->options(function (Produk $record) {
                                        $options = [];
                                        $record->spesifikasiProduk->each(function ($spek) use (&$options) {
                                            $options[$spek->spesifikasi->nama_spesifikasi] = $spek->pilihan;
                                        });
                                        return $options;
                                    })

                            ])
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                // Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('transaksi.id')
                    ->label('Transaction ID')
                    ->description('Unique identifier for this transaction')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor')
                    ->description('Supplier or vendor name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Product')
                    ->description('Product name and details')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->description('Material type used')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuantitas')
                    ->label('Quantity')
                    ->description('Number of items ordered')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Unit Price')
                    ->description('Price per single unit')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('spesifikasi')
                    ->label('Specifications')
                    ->description('Product specifications and details')
                    ->json()
                    ->listWithLineBreaks()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->description('Record creation timestamp')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->description('Last modification timestamp')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
