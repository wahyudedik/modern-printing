<?php

namespace App\Filament\App\Resources\TransaksiResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TransaksiItemSpecificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'transaksiItemSpecifications';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Specification Details')
                    ->description('Enter the specification details for this transaction item')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),

                                Forms\Components\Select::make('transaksi_item_id')
                                    ->relationship('transaksiItem', 'id')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),

                                Forms\Components\Select::make('spesifikasi_produk_id')
                                    ->relationship(
                                        'spesifikasiProduk.spesifikasi',
                                        'nama_spesifikasi'
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),

                                Forms\Components\Select::make('bahan_id')
                                    ->relationship('bahan', 'nama_bahan')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                            ])->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter value')
                                    ->hint('Maximum 255 characters'),

                                Forms\Components\Select::make('input_type')
                                    ->required()
                                    ->options([
                                        'text' => 'Text',
                                        'number' => 'Number',
                                        'date' => 'Date',
                                        'select' => 'Select'
                                    ])
                                    ->native(false)
                                    ->searchable(),

                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->maxValue(9999999999.99)
                                    ->mask('999,999,999.99')
                                    ->placeholder('Enter price')
                                    ->hint('Maximum value: Rp 9,999,999,999.99'),
                            ])->columns(2)
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('spesifikasiProduk.spesifikasi.nama_spesifikasi')
                    ->label('Specification')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->icon('heroicon-m-document-text'),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->icon('heroicon-m-cube'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->icon('heroicon-m-variable'),
                Tables\Columns\TextColumn::make('input_type')
                    ->label('Input Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'text' => 'info',
                        'number' => 'success',
                        'date' => 'warning',
                        'select' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->icon('heroicon-m-currency-dollar'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->tooltip('View details')
                    ->modalWidth('xl')
                    ->slideOver(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
