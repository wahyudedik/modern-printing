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
                Forms\Components\Section::make('Harga Grosir Details')
                    ->description('Atur detail harga grosir untuk bahan ini.')
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),
                                Forms\Components\TextInput::make('min_quantity')
                                    ->label('Minimum Quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->hint('Minimum order quantity for wholesale price')
                                    ->suffixIcon('heroicon-m-cube'),
                                Forms\Components\TextInput::make('max_quantity')
                                    ->label('Maximum Quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Unlimited')
                                    ->hint('Leave empty for unlimited quantity')
                                    ->suffixIcon('heroicon-m-cube-transparent'),
                            ])->columns(1),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('harga')
                                    ->label('Price Per Unit')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->hint('Enter the wholesale price per unit')
                                    ->suffixIcon('heroicon-m-banknotes'),
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
                    ->description(fn($record): string => "Minimum quantity required: {$record->min_quantity} units")
                    ->icon('heroicon-m-cube')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_quantity')
                    ->label('Maximum Quantity')
                    ->numeric()
                    ->description(fn($record) => $record->max_quantity ? "Maximum limit: {$record->max_quantity} units" : 'No upper limit')
                    ->icon('heroicon-m-cube-transparent')
                    ->sortable()
                    ->placeholder('∞'),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Price Per Unit')
                    ->money('IDR')
                    ->description(fn($record): string => "Wholesale price: Rp " . number_format($record->harga, 0, ',', '.'))
                    ->icon('heroicon-m-banknotes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Material')
                    ->description(fn($record): string => "Material: {$record->bahan->nama_bahan}")
                    ->icon('heroicon-m-beaker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->description(fn($record): string => "Created: " . $record->created_at->diffForHumans())
                    ->icon('heroicon-m-clock')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y H:i')
                    ->description(fn($record): string => "Updated: " . $record->updated_at->diffForHumans())
                    ->icon('heroicon-m-arrow-path')
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
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->tooltip('Edit harga grosir')
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->tooltip('Hapus harga grosir')
                    ->modalDescription('Apakah Anda yakin ingin menghapus harga grosir ini? Tindakan ini tidak dapat dibatalkan.')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->modalDescription('Apakah Anda yakin ingin menghapus semua harga grosir yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                        ->deselectRecordsAfterCompletion()
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s');
    }
}
