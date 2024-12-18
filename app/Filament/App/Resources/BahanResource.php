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
                            ->columnSpanFull(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('harga_per_satuan')
                                    ->label('Harga per Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->maxValue(9999999999)
                                    ->placeholder('0'),

                                Forms\Components\TextInput::make('satuan')
                                    ->label('Satuan')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('Contoh: m2, lembar'),

                                Forms\Components\TextInput::make('stok')
                                    ->label('Stok')
                                    ->numeric()
                                    ->nullable()
                                    ->placeholder('Masukkan jumlah stok'),
                            ])->columns(3),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_per_satuan')
                    ->label('Harga per Satuan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai'),
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
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Apakah anda yakin ingin menghapus data yang dipilih?'),
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
