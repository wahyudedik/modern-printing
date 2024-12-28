<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Bahan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\App\Resources\BahanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\BahanResource\RelationManagers;
use App\Filament\App\Resources\BahanResource\RelationManagers\UkuranBahanRelationManager;
use App\Filament\App\Resources\BahanResource\RelationManagers\WholesalePriceRelationManager;

class BahanResource extends Resource
{
    protected static ?string $model = Bahan::class;

    protected static ?string $label = 'Bahan';

    protected static ?string $pluralLabel = 'Bahan';

    protected static ?string $navigationLabel = 'Bahan';

    protected static ?string $title = 'Bahan';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'bahan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bahan dan Alat';

    protected static ?string $navigationIcon = 'si-materialformkdocs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Bahan')
                    ->description('Masukkan informasi detail bahan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bahan')
                            ->label('Nama Bahan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama bahan')
                            ->autocomplete('off')
                            ->autofocus()
                            ->columnSpanFull(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('harga_per_satuan')
                                    ->label('Harga per Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->maxValue(9999999999)
                                    ->placeholder('0')
                                    ->hint('Masukkan harga dalam Rupiah')
                                    ->live(),
                            ])->columnSpanFull(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('satuan')
                                    ->label('Satuan')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('Contoh: m2, lembar')
                                    ->datalist([
                                        'm2',
                                        'lembar',
                                        'roll',
                                        'meter',
                                        'yard',
                                        'pcs',
                                        'box',
                                        'pack',
                                        'botol',
                                        'liter',
                                        'galon',
                                        'kaleng',
                                        'set',
                                        'unit',
                                        'kg',
                                        'gram'
                                    ])
                                    ->autocomplete('off'),
                            ])->columnSpanFull(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('stok')
                                    ->label('Stok')
                                    ->numeric()
                                    ->nullable()
                                    ->placeholder('Masukkan jumlah stok')
                                    ->suffixIcon('heroicon-m-cube')
                                    ->live()
                                    ->hint('Kosongkan jika tidak ada stok'),
                            ])->columnSpanFull(),
                    ])->columns(2)
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable()
                    ->searchable(isIndividual: true)
                    ->tooltip(fn($record) => "Nama Bahan: {$record->nama_bahan}")
                    ->wrap(),
                Tables\Columns\TextColumn::make('harga_per_satuan')
                    ->label('Harga per Satuan')
                    ->money('IDR', true)
                    ->sortable()
                    ->alignment('center')
                    ->color('success')
                    ->icon('heroicon-m-currency-dollar')
                    ->tooltip(fn($record) => "Harga: Rp " . number_format($record->harga_per_satuan, 0, ',', '.')),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignment('center')
                    ->tooltip('Satuan Bahan'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->alignment('center')
                    ->badge()
                    ->color(fn($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->description(fn($record) => $record->stok > 0 ? 'Tersedia' : 'Habis')
                    ->tooltip(fn($record) => "Stok tersedia: {$record->stok}"),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('d M Y H:i:s')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('d M Y H:i:s'))
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->tooltip('Lihat Detail'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-m-pencil-square')
                        ->color('warning')
                        ->tooltip('Edit Data'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->tooltip('Hapus Data')
                        ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->modalHeading('Hapus Data Terpilih')
                        ->modalDescription('Apakah anda yakin ingin menghapus data yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            WholesalePriceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBahans::route('/'),
            'create' => Pages\CreateBahan::route('/create'),
            'edit' => Pages\EditBahan::route('/{record}/edit'),
        ];
    }
}
