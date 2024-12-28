<?php

namespace App\Filament\App\Resources\TransaksiResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransaksiItem;
use Filament\Facades\Filament;
use Filament\Support\Enums\Alignment;
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
                                    ->preload()
                                    ->native(false)
                                    ->label('Product')
                                    ->placeholder('Select a product')
                                    ->loadingMessage('Loading products...')
                                    ->searchPrompt('Search products'),
                                Forms\Components\Select::make('bahan_id')
                                    ->relationship('bahan', 'nama_bahan')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->label('Material')
                                    ->placeholder('Select a material')
                                    ->loadingMessage('Loading materials...')
                                    ->searchPrompt('Search materials'),
                            ])->columns(2),

                        Forms\Components\Section::make('Pricing & Quantity')
                            ->description('Enter the quantity and price details')
                            ->icon('heroicon-o-currency-dollar')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('kuantitas')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->label('Quantity')
                                    ->placeholder('Enter quantity')
                                    ->suffixIcon('heroicon-m-calculator')
                                    ->hint('Minimum: 1'),

                                Forms\Components\TextInput::make('harga_satuan')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->label('Unit Price')
                                    ->placeholder('Enter unit price')
                                    ->suffixIcon('heroicon-m-banknotes')
                                    ->hint('Price per unit'),
                            ])->columns(2),

                        Forms\Components\Section::make('Specifications')
                            ->description('Select product specifications')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Select::make('spesifikasi')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->placeholder('Choose specifications')
                                    ->loadingMessage('Loading specifications...')
                                    ->options(function (TransaksiItem $record) {
                                        $options = [];
                                        $record->produk->spesifikasiProduk->each(function ($spek) use (&$options) {
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
                    ->sortable()
                    ->icon('heroicon-m-identification')
                    ->copyable()
                    ->copyMessage('ID copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Product')
                    ->description('Product name and details')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-shopping-bag')
                    ->wrap()
                    ->tooltip('Click to view product details'),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->description('Material type used')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-cube')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('kuantitas')
                    ->label('Quantity')
                    ->description('Number of items ordered')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-m-calculator')
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Unit Price')
                    ->description('Price per single unit')
                    ->money('IDR')
                    ->sortable()
                    ->icon('heroicon-m-banknotes')
                    ->color('success')
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('spesifikasi')
                    ->label('Specifications')
                    ->description('Product specifications and details')
                    ->formatStateUsing(fn($state) => collect($state)->map(function ($item, $key) {
                        return "$key: $item";
                    })->join(', '))
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->description('Record creation timestamp')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-calendar')
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->description('Last modification timestamp')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-clock')
                    ->since()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
