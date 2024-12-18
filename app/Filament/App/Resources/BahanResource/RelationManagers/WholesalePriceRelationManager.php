<?php

namespace App\Filament\App\Resources\BahanResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WholesalePriceRelationManager extends RelationManager
{
    protected static string $relationship = 'wholesalePrice';

    protected static ?string $title = 'Harga Grosir';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wholesale Price Details')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),
                                Forms\Components\Hidden::make('bahan_id')
                                    ->default(fn($record) => $record->id),
                                Forms\Components\TextInput::make('min_quantity')
                                    ->label('Minimum Quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                                Forms\Components\TextInput::make('max_quantity')
                                    ->label('Maximum Quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Unlimited'),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('price_per_unit')
                                    ->label('Price Per Unit')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->step(0.01),
                                Forms\Components\Hidden::make('produk_id')
                                    ->default(fn($record) => $record->produk_id),
                            ])->columns(1),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                // Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('min_quantity')
                    ->label('Minimum Quantity')
                    ->numeric()
                    ->description('Minimum quantity required for wholesale price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_quantity')
                    ->label('Maximum Quantity')
                    ->numeric()
                    ->description('Maximum quantity limit (optional)')
                    ->sortable()
                    ->placeholder('Unlimited'),
                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Price Per Unit')
                    ->money('Rp')
                    ->description('Wholesale price per unit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->description('Associated material')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->description('Record creation date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->description('Last update date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-s-plus')->label('Tambah Harga Grosir'),
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
