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
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Bahan'),

                        Forms\Components\Textarea::make('deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Deskripsi'),

                        Forms\Components\KeyValue::make('spesifikasi')
                            ->label('Spesifikasi')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('supplier')
                            ->maxLength(255)
                            ->label('Supplier'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
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
                    ->sortable()
                    ->description(fn(Bahan $record): string => $record->deskripsi ?? '-')
                    ->weight('medium')
                    ->copyable(),
                Tables\Columns\TextColumn::make('spesifikasi')
                    ->label('Spesifikasi')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->color('gray')
                    ->getStateUsing(function (Bahan $record) {
                        $specs = $record->spesifikasi;
                        if (!is_array($specs)) {
                            return [];
                        }

                        return array_map(function ($value, $key) {
                            return "{$key}: {$value}";
                        }, $specs, array_keys($specs));
                    }),
                Tables\Columns\TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-building-office')
                    ->iconColor('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
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
                    Tables\Actions\ViewAction::make()
                        ->form([
                            Forms\Components\TextInput::make('nama_bahan')
                                ->label('Nama Bahan')
                                ->required(),
                            Forms\Components\Textarea::make('deskripsi')
                                ->label('Deskripsi'),
                            Forms\Components\KeyValue::make('spesifikasi')
                                ->label('Spesifikasi'),
                            Forms\Components\TextInput::make('supplier')
                                ->label('Supplier'),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                        ]),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Apakah anda yakin ingin menghapus data yang dipilih?'),
                    Tables\Actions\BulkAction::make('update')
                        ->label('Update Data')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Forms\Components\TextInput::make('nama_bahan')
                                ->label('Nama Bahan'),
                            Forms\Components\Textarea::make('deskripsi')
                                ->label('Deskripsi'),
                            Forms\Components\KeyValue::make('spesifikasi')
                                ->label('Spesifikasi'),
                            Forms\Components\TextInput::make('supplier')
                                ->label('Supplier'),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update($data);
                            });
                        }),
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
            'index' => Pages\ListBahans::route('/'),
            'create' => Pages\CreateBahan::route('/create'),
            'edit' => Pages\EditBahan::route('/{record}/edit'),
        ];
    }
}
