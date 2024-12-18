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
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('nama_spesifikasi')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Spesifikasi')
                                    ->helperText('Masukkan nama spesifikasi produk'),
                                Forms\Components\Select::make('tipe_input')
                                    ->required()
                                    ->options([
                                        'text' => 'Text',
                                        'number' => 'Number',
                                        'select' => 'Select',
                                        'checkbox' => 'Checkbox',
                                    ])
                                    ->label('Tipe Input')
                                    ->helperText('Pilih tipe input untuk spesifikasi ini'),
                                Forms\Components\TextInput::make('satuan')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Satuan')
                                    ->helperText('Masukkan satuan untuk spesifikasi ini'),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
