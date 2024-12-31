<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SpesifikasiResource\Pages;
use App\Filament\App\Resources\SpesifikasiResource\RelationManagers;
use App\Models\Spesifikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpesifikasiResource extends Resource
{
    protected static ?string $model = Spesifikasi::class;
    protected static ?string $navigationLabel = 'Spesifikasi Produk';
    protected static ?string $modelLabel = 'Spesifikasi Produk';
    protected static ?string $pluralModelLabel = 'Spesifikasi Produk';
    protected static ?string $slug = 'spesifikasi';
    protected static ?int $navigationSort = 2; //29
    protected static bool $isScopedToTenant = true;
    protected static ?string $tenantOwnershipRelationshipName = 'vendor';
    protected static ?string $tenantRelationshipName = 'spesifikasi';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Spesifikasi')
                    ->description('Masukkan informasi spesifikasi produk')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('nama_spesifikasi')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Spesifikasi')
                                    ->placeholder('Contoh: Berat, Panjang, Warna')
                                    ->helperText('Masukkan nama spesifikasi produk')
                                    ->autocomplete('off'),
                                Forms\Components\Select::make('tipe_input')
                                    ->required()
                                    ->searchable()
                                    ->options([
                                        'number' => 'Number',
                                        'select' => 'Select',
                                    ])
                                    ->label('Tipe Input')
                                    ->helperText('Pilih tipe input untuk spesifikasi ini')
                                    ->native(false),
                                Forms\Components\Select::make('satuan')
                                    ->required()
                                    ->searchable()
                                    ->options([
                                        'kg' => 'Kilogram (kg)',
                                        'g' => 'Gram (g)',
                                        'mg' => 'Miligram (mg)',
                                        'l' => 'Liter (l)',
                                        'ml' => 'Mililiter (ml)',
                                        'cm' => 'Centimeter (cm)',
                                        'm' => 'Meter (m)',
                                        'mm' => 'Milimeter (mm)',
                                        'pcs' => 'Pieces (pcs)',
                                        'unit' => 'Unit',
                                        'pack' => 'Pack',
                                        'box' => 'Box',
                                        'lusin' => 'Lusin',
                                        'kodi' => 'Kodi',
                                        'rim' => 'Rim',
                                        'roll' => 'Roll',
                                        'lembar' => 'Lembar',
                                        'buah' => 'Buah'
                                    ])
                                    ->label('Satuan')
                                    ->helperText('Pilih satuan untuk spesifikasi ini')
                                    ->native(false),
                            ])->columns(2)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_spesifikasi')
                    ->label('Nama Spesifikasi')
                    ->description(fn(Spesifikasi $record): string => "Tipe: {$record->tipe_input}")
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Klik untuk menyalin')
                    ->wrap(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('satuan')
                    ->options([
                        'kg' => 'Kilogram (kg)',
                        'g' => 'Gram (g)',
                        'mg' => 'Miligram (mg)',
                        'l' => 'Liter (l)',
                        'ml' => 'Mililiter (ml)',
                        'cm' => 'Centimeter (cm)',
                        'm' => 'Meter (m)',
                        'mm' => 'Milimeter (mm)',
                        'pcs' => 'Pieces (pcs)',
                        'unit' => 'Unit',
                        'pack' => 'Pack',
                        'box' => 'Box',
                        'lusin' => 'Lusin',
                        'kodi' => 'Kodi',
                        'rim' => 'Rim',
                        'roll' => 'Roll',
                        'lembar' => 'Lembar',
                        'buah' => 'Buah'
                    ])
                    ->label('Filter Satuan')
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\SelectFilter::make('tipe_input')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'select' => 'Select'
                    ])
                    ->label('Filter Tipe Input')
                    ->searchable()
                    ->preload()
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip('Edit data')
                    ->icon('heroicon-m-pencil-square'),
                    Tables\Actions\ViewAction::make()
                    ->tooltip('Lihat data')
                    ->icon('heroicon-m-eye'),
                    Tables\Actions\DeleteAction::make()
                    ->tooltip('Hapus data')
                    ->icon('heroicon-m-trash')
                    ->modal()
                    ->modalIcon('heroicon-m-trash')
                    ->modalDescription('Are you sure you want to delete this item? This action cannot be undone.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->modalIcon('heroicon-m-trash')
                        ->modalDescription('Are you sure you want to delete these items? This action cannot be undone.'),
                ])->icon('heroicon-m-cog-6-tooth'),
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
            'index' => Pages\ListSpesifikasis::route('/'),
            'create' => Pages\CreateSpesifikasi::route('/create'),
            'edit' => Pages\EditSpesifikasi::route('/{record}/edit'),
        ];
    }
}
