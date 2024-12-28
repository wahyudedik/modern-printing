<?php

namespace App\Filament\App\Resources;

use Carbon\Carbon;
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
                    ->description('Masukkan informasi detail alat')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('nama_alat')
                            ->label('Nama Alat')
                            ->placeholder('Masukkan nama alat')
                            ->required()
                            ->maxLength(255)
                            ->autocomplete(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('merk')
                                    ->placeholder('Masukkan merk alat')
                                    ->prefixIcon('heroicon-m-building-office-2')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('model')
                                    ->placeholder('Masukkan model alat')
                                    ->prefixIcon('heroicon-m-tag')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('spesifikasi')
                            ->placeholder('Masukkan spesifikasi alat')
                            ->required()
                            ->rows(3)
                            ->hint('Deskripsikan spesifikasi alat secara detail'),
                    ]),
                Forms\Components\Section::make('Status dan Kapasitas')
                    ->description('Atur status dan kapasitas alat')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'maintenance' => 'Maintenance',
                                        'rusak' => 'Rusak',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-signal')
                                    ->placeholder('Pilih status alat'),
                                Forms\Components\DatePicker::make('tanggal_pembelian')
                                    ->required()
                                    ->placeholder('Pilih tanggal pembelian')
                                    ->prefixIcon('heroicon-m-calendar')
                                    ->native(false),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kapasitas_cetak_per_jam')
                                    ->numeric()
                                    ->required()
                                    ->label('Kapasitas Cetak/Jam')
                                    ->placeholder('Masukkan kapasitas per jam')
                                    ->prefixIcon('heroicon-m-clock')
                                    ->suffix('unit/jam'),
                                Forms\Components\Select::make('tersedia')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                        'belum_diketahui' => 'Belum Diketahui',
                                        'antrian' => 'Antrian',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->placeholder('Pilih ketersediaan')
                                    ->prefixIcon('heroicon-m-check-circle'),
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
                    ->copyable()
                    ->tooltip('Klik untuk menyalin')
                    ->icon('heroicon-m-wrench'),
                Tables\Columns\TextColumn::make('merk')
                    ->wrap()
                    ->searchable()
                    ->icon('heroicon-m-building-office-2')
                    ->description(fn($record) => $record->model),
                Tables\Columns\TextColumn::make('model')
                    ->wrap()
                    ->searchable()
                    ->icon('heroicon-m-tag')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('spesifikasi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->icon('heroicon-m-document-text')
                    ->wrap()
                    ->markdown(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'warning' => 'maintenance',
                        'danger' => 'rusak',
                    ])
                    ->icon(fn(string $state): string => match ($state) {
                        'aktif' => 'heroicon-m-check-circle',
                        'maintenance' => 'heroicon-m-wrench',
                        'rusak' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->date('d M Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar')
                    ->description(fn($record) => 'Usia: ' . $record->tanggal_pembelian->diffForHumans()),
                Tables\Columns\TextColumn::make('kapasitas_cetak_per_jam')
                    ->label('Kapasitas/Jam')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-m-clock')
                    ->alignment('center')
                    ->suffix(' unit/jam'),
                Tables\Columns\TextColumn::make('tersedia')
                    ->searchable()
                    ->icon('heroicon-m-check-circle')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'ya' => 'success',
                        'tidak' => 'danger',
                        'belum_diketahui' => 'gray',
                        'antrian' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->icon('heroicon-m-clock')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->icon('heroicon-m-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options([
                        'aktif' => 'Aktif',
                        'maintenance' => 'Maintenance',
                        'rusak' => 'Rusak',
                    ]),
                Tables\Filters\Filter::make('tanggal_pembelian')
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['from'] && !$data['until']) {
                            return null;
                        }

                        if ($data['from'] && !$data['until']) {
                            return 'Dari ' . Carbon::parse($data['from'])->format('d M Y');
                        }

                        if (!$data['from'] && $data['until']) {
                            return 'Sampai ' . Carbon::parse($data['until'])->format('d M Y');
                        }

                        return 'Dari ' . Carbon::parse($data['from'])->format('d M Y') . ' sampai ' . Carbon::parse($data['until'])->format('d M Y');
                    })
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d M Y'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d M Y'),
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
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->slideOver()
                        ->form([
                            Forms\Components\Section::make('Equipment Details')
                                ->description('Basic information about the equipment')
                                ->schema([
                                    Forms\Components\TextInput::make('nama_alat')
                                        ->label('Equipment Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->placeholder('Enter equipment name')
                                        ->autocomplete('off'),
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('merk')
                                                ->label('Brand')
                                                ->maxLength(255)
                                                ->placeholder('Enter brand name'),
                                            Forms\Components\TextInput::make('model')
                                                ->maxLength(255)
                                                ->placeholder('Enter model number'),
                                        ]),
                                ]),
                            Forms\Components\Section::make('Technical Specifications')
                                ->schema([
                                    Forms\Components\RichEditor::make('spesifikasi')
                                        ->label('Specifications')
                                        ->required()
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'bulletList',
                                            'orderedList',
                                        ])
                                        ->columnSpanFull(),
                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'aktif' => 'Aktif',
                                            'maintenance' => 'Maintenance',
                                            'rusak' => 'Rusak',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->searchable()
                                        ->preload(),
                                    Forms\Components\DatePicker::make('tanggal_pembelian')
                                        ->label('Purchase Date')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('d M Y'),
                                    Forms\Components\TextInput::make('kapasitas_cetak_per_jam')
                                        ->label('Printing Capacity (per hour)')
                                        ->numeric()
                                        ->required()
                                        ->suffix('units/hour'),
                                ]),
                            Forms\Components\Section::make('Additional Information')
                                ->schema([
                                    Forms\Components\MarkdownEditor::make('keterangan')
                                        ->label('Notes')
                                        ->columnSpanFull()
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'bulletList',
                                            'orderedList',
                                        ]),
                                ]),
                        ]),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->modalDescription('Are you sure you want to delete this equipment? This action cannot be undone.')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Yes, delete it')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Equipment deleted')
                                ->body('The equipment has been deleted successfully.')
                                ->duration(5000)
                        ),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalDescription('Are you sure you want to delete these equipments? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Equipments deleted')
                                ->body('The selected equipments have been deleted successfully.')
                                ->duration(5000)
                        ),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->modalIcon('heroicon-o-arrow-path')
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
                                ->native(false)
                                ->searchable()
                                ->preload()
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['status' => $data['status']]);
                            });
                            Notification::make()
                                ->success()
                                ->title('Status updated')
                                ->body('The status has been updated for the selected equipments.')
                                ->duration(5000)
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
