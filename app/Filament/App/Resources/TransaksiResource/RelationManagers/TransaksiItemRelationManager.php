<?php

namespace App\Filament\App\Resources\TransaksiResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiItemRelationManager extends RelationManager
{
    protected static string $relationship = 'transaksiItem';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\Hidden::make('vendor_id')
                            ->default(Filament::getTenant()->id),
                        Forms\Components\Select::make('produk_id')
                            ->relationship('produk', 'nama_produk')
                            ->required(),
                        Forms\Components\Select::make('bahan_id')
                            ->relationship('bahan', 'nama_bahan')
                            ->required(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0),

                                Forms\Components\TextInput::make('wholesale_price_per_unit')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0),
                            ])->columns(3),

                        Forms\Components\KeyValue::make('specs')
                            ->required()
                    ])->columns(2)
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('wholesale_price_per_unit')
                    ->label('Wholesale Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('specs')
                    ->label('Specifications')
                    ->listWithLineBreaks()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
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
