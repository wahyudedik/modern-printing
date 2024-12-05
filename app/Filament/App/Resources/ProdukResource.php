<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProdukResource\Pages;
use App\Filament\App\Resources\ProdukResource\RelationManagers;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $label = 'Produk';

    protected static ?string $pluralLabel = 'Produk';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $title = 'Produk';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'produk';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Produk';

    protected static ?string $navigationIcon = 'carbon-product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Gambar Produk')
                            ->schema([
                                Forms\Components\FileUpload::make('gambar')
                                    ->multiple()
                                    ->reorderable()
                                    ->imageEditor()
                                    ->imagePreviewHeight('100')
                                    ->directory('produk-images')
                                    ->maxFiles(5)
                                    ->required()
                                    ->columnSpanFull()
                                    ->imageEditorViewportWidth(3)
                                    ->imageEditorViewportHeight(3),
                            ]),
                        Forms\Components\Section::make('Informasi Produk')
                            ->schema([
                                Forms\Components\TextInput::make('nama_produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', str()->slug($state)) : null),
                                Forms\Components\Hidden::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Produk::class, 'slug', ignoreRecord: true),
                                Forms\Components\TextInput::make('kategori')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('bahan')
                                    ->multiple()
                                    ->relationship('bahan', 'nama_bahan')
                                    ->preload(),
                                Forms\Components\Select::make('alat')
                                    ->multiple()
                                    ->relationship('alat', 'nama_alat')
                                    ->preload(),
                            ]),
                    ])->columnSpanFull(),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Harga & Stok')
                            ->schema([
                                Forms\Components\TextInput::make('harga')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                        $harga = floatval($state);
                                        $diskon = floatval($get('diskon') ?? 0);
                                        $total = $harga - $diskon;
                                        $set('total_harga', $total);
                                    })
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('diskon')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                        $harga = floatval($get('harga') ?? 0);
                                        $diskon = floatval($state);
                                        $total = $harga - $diskon;
                                        $set('total_harga', $total);
                                    })
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('total_harga')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('minimal_qty')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(255),
                            ])->columns(2),
                        Forms\Components\Section::make('Deskripsi')
                            ->schema([
                                Forms\Components\RichEditor::make('deskripsi')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('nama_produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('bahan.nama_bahan')
                    ->label('Bahan')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('alat.nama_alat')
                    ->label('Alat')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('harga')
                    ->money('idr')
                    ->sortable()
                    ->color('success'),
                Tables\Columns\TextColumn::make('diskon')
                    ->money('idr')
                    ->sortable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->money('idr')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('minimal_qty')
                    ->sortable()
                    ->label('Minimal Qty')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state > 10 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->options(fn() => Produk::pluck('kategori', 'kategori')),
                Tables\Filters\Filter::make('harga')
                    ->form([
                        Forms\Components\TextInput::make('harga_from')
                            ->label('Harga Dari')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('harga_until')
                            ->label('Harga Sampai')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['harga_from'],
                                fn(Builder $query, $value): Builder => $query->where('harga', '>=', $value),
                            )
                            ->when(
                                $data['harga_until'],
                                fn(Builder $query, $value): Builder => $query->where('harga', '<=', $value),
                            );
                    })->columns(2),
                Tables\Filters\Filter::make('minimal_qty')
                    ->form([
                        Forms\Components\TextInput::make('minimal_qty_from')
                            ->label('Minimal Qty Dari')
                            ->numeric(),
                        Forms\Components\TextInput::make('minimal_qty_until')
                            ->label('Minimal Qty Sampai')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['minimal_qty_from'],
                                fn(Builder $query, $value): Builder => $query->where('minimal_qty', '>=', $value),
                            )
                            ->when(
                                $data['minimal_qty_until'],
                                fn(Builder $query, $value): Builder => $query->where('minimal_qty', '<=', $value),
                            );
                    })->columns(2),
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
                                fn(Builder $query, $value): Builder => $query->whereDate('created_at', '>=', $value),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $value): Builder => $query->whereDate('created_at', '<=', $value),
                            );
                    })->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->modalWidth('4xl')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk updated')
                                ->body('Produk has been updated successfully.')
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalWidth('sm')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk deleted')
                                ->body('Produk has been deleted successfully.')
                        ),
                    Tables\Actions\Action::make('print')
                        ->label('Download PDF Data Produk')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(function (Produk $record) {
                            // Get fresh data with eager loading of relationships
                            $produk = Produk::where('id', $record->id)
                                ->with([
                                    'vendor',
                                    'bahan' => function ($query) {
                                        $query->select('bahans.id', 'nama_bahan');
                                    },
                                    'alat' => function ($query) {
                                        $query->select('alats.id', 'nama_alat');
                                    }
                                ])
                                ->first();

                            // Convert to array to ensure data accessibility in view
                            $produkArray = $produk->toArray();

                            $pdf = Pdf::loadView('pdf.produk', [
                                'produk' => $produkArray
                            ]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'Produk-' . $produk->nama_produk . '.pdf');
                        }),

                    Tables\Actions\Action::make('duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function ($record) {
                            $duplicate = $record->replicate();
                            $duplicate->nama_produk = $duplicate->nama_produk . ' (copy)';
                            $duplicate->slug = Str::slug($duplicate->nama_produk);
                            $duplicate->save();
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk duplicated')
                                ->body('Produk has been duplicated successfully.')
                        ),
                ])
                    ->tooltip('Actions')
                    ->color('primary')
                    ->icon('heroicon-m-ellipsis-horizontal')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updatePrice')
                        ->icon('heroicon-o-currency-dollar')
                        ->form([
                            Forms\Components\TextInput::make('harga')
                                ->label('New Price')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('diskon')
                                ->label('Discount')
                                ->numeric(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update([
                                    'harga' => $data['harga'],
                                    'diskon' => $data['diskon'],
                                    'total_harga' => $data['harga'] - ($data['diskon'] ?? 0)
                                ]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('updateStock')
                        ->icon('heroicon-o-cube')
                        ->form([
                            Forms\Components\TextInput::make('minimal_qty')
                                ->label('New Minimal QTY')
                                ->required()
                                ->numeric(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update([
                                    'minimal_qty' => $data['minimal_qty']
                                ]);
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
