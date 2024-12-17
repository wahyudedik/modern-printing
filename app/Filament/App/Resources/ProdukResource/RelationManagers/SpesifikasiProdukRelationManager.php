<?php

namespace App\Filament\App\Resources\ProdukResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpesifikasiProdukRelationManager extends RelationManager
{
    protected static string $relationship = 'spesifikasiProduk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('vendor_id')
                    ->default(Filament::getTenant()->id),
                Forms\Components\Section::make('Spesifikasi Produk')
                    ->description('Masukkan detail spesifikasi produk')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->maxLength(100)
                                    ->label('Nama Spesifikasi'),
                                Forms\Components\Textarea::make('options')
                                    ->required()
                                    ->label('Pilihan Spesifikasi')
                                    ->helperText('Masukkan pilihan spesifikasi produk')
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
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Spesifikasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('options')
                    ->label('Pilihan Spesifikasi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): string {
                        return $column->getRecord()->options;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
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
