<?php

namespace App\Filament\App\Resources\ProdukResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Bahan;
use App\Models\SpesifikasiProduk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SpesifikasiProdukRelationManager extends RelationManager
{
    protected static string $relationship = 'spesifikasiProduk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Spesifikasi Produk')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),
                                Select::make('spesifikasi_id')
                                    ->relationship('spesifikasi', 'nama_spesifikasi')
                                    ->required(),

                                Select::make('wajib_diisi')
                                    ->options([
                                        '1' => 'Ya',
                                        '0' => 'Tidak'
                                    ])
                                    ->required(),
                                Select::make('pilihan')
                                    ->multiple()
                                    ->relationship('bahans', 'nama_bahan')
                                    ->options(function () {
                                        return Bahan::where('vendor_id', Filament::getTenant()->id)
                                            ->pluck('nama_bahan', 'id');
                                    })
                                    ->preload()
                                    ->searchable()
                            ])
                            ->columns(2)
                    ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('spesifikasi.nama_spesifikasi')
                    ->label('Nama Spesifikasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wajib_diisi')
                    ->label('Wajib Diisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bahans.nama_bahan')
                    ->label('Pilihan Material')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->bahans->pluck('nama_bahan')),
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
