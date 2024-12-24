<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Alat;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\App\Resources\AlatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\AlatResource\RelationManagers;

class AlatResource extends Resource
{
    protected static ?string $model = Alat::class;

    protected static ?string $label = 'Alat';

    protected static ?string $pluralLabel = 'Alat';

    protected static ?string $navigationLabel = 'Alat';

    protected static ?string $title = 'Alat';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'alat';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Bahan dan Alat';

    protected static ?string $navigationIcon = 'carbon-calendar-tools';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Alat')
                    ->schema([
                        Forms\Components\TextInput::make('nama_alat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('merk')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('model')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('spesifikasi')
                            ->required()
                            ->rows(3),
                    ]),
                Forms\Components\Section::make('Status dan Kapasitas')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'maintenance' => 'Maintenance',
                                        'rusak' => 'Rusak',
                                    ])
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_pembelian')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kapasitas_cetak_per_jam')
                                    ->numeric()
                                    ->required()
                                    ->label('Kapasitas Cetak/Jam'),
                                Forms\Components\Select::make('tersedia')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                        'belum_diketahui' => 'Belum Diketahui',
                                        'antrian' => 'Antrian',
                                    ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_alat')
                    ->label('Nama Alat')
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('merk')
                    ->wrap()
                    ->searchable()
                    ->icon('heroicon-m-building-office-2'),
                Tables\Columns\TextColumn::make('model')
                    ->wrap()
                    ->searchable()
                    ->icon('heroicon-m-tag'),
                Tables\Columns\TextColumn::make('spesifikasi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->icon('heroicon-m-document-text'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'warning' => 'maintenance',
                        'danger' => 'rusak',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->date()
                    ->sortable()
                    ->icon('heroicon-m-calendar'),
                Tables\Columns\TextColumn::make('kapasitas_cetak_per_jam')
                    ->label('Kapasitas/Jam')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-m-clock')
                    ->alignment('center'),
                Tables\Columns\TextColumn::make('tersedia')
                    ->searchable()
                    ->icon('heroicon-m-check-circle'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-m-clock')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-m-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'maintenance' => 'Maintenance',
                        'rusak' => 'Rusak',
                    ]),
                Tables\Filters\Filter::make('tanggal_pembelian')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pembelian', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pembelian', '<=', $date),
                            );
                    })->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->form([
                            Forms\Components\TextInput::make('nama_alat')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('merk')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('model')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('spesifikasi')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'maintenance' => 'Maintenance',
                                    'rusak' => 'Rusak',
                                ])
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_pembelian')
                                ->required(),
                            Forms\Components\TextInput::make('kapasitas_cetak_per_jam')
                                ->numeric()
                                ->required(),
                            Forms\Components\Textarea::make('keterangan')
                                ->columnSpanFull(),
                        ]),
                    Tables\Actions\DeleteAction::make()
                        ->modalDescription('Are you sure you want to delete this equipment? This action cannot be undone.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Equipment deleted')
                                ->body('The equipment has been deleted successfully.')
                        ),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Are you sure you want to delete these equipments? This action cannot be undone.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Equipments deleted')
                                ->body('The selected equipments have been deleted successfully.')
                        ),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->modalHeading('Update Status for Selected Equipments')
                        ->modalDescription('Choose a new status for the selected equipments.')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('New Status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'maintenance' => 'Maintenance',
                                    'rusak' => 'Rusak',
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['status' => $data['status']]);
                            });
                            Notification::make()
                                ->success()
                                ->title('Status updated')
                                ->body('The status has been updated for the selected equipments.')
                                ->send();
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
            'index' => Pages\ListAlats::route('/'),
            'create' => Pages\CreateAlat::route('/create'),
            'edit' => Pages\EditAlat::route('/{record}/edit'),
        ];
    }
}
