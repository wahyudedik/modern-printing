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
use App\Filament\App\Resources\BahanResource\RelationManagers\WholesalePriceRelationManager;
use App\Filament\App\Resources\ProdukResource\RelationManagers\SpesifikasiProdukRelationManager;

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
                            ->description('Upload gambar produk dengan kualitas terbaik')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                Forms\Components\FileUpload::make('gambar')
                                    ->multiple()
                                    ->reorderable()
                                    ->imageEditor()
                                    ->imagePreviewHeight('250')
                                    ->directory('produk-images')
                                    ->maxFiles(5)
                                    ->required()
                                    ->columnSpanFull()
                                    ->imageEditorViewportWidth(3)
                                    ->imageEditorViewportHeight(3)
                                    ->downloadable()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Format yang didukung: JPG, PNG, WEBP. Maksimal 5 gambar.')
                                    ->panelAspectRatio('16:9'),
                            ]),
                        Forms\Components\Section::make('Informasi Produk')
                            ->description('Masukkan informasi detail produk')
                            ->icon('heroicon-o-shopping-bag')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nama_produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama produk')
                                    ->live(onBlur: true)
                                    ->autofocus(),
                                Forms\Components\Select::make('kategori_id')
                                    ->relationship('kategori', 'nama_kategori')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\Hidden::make('vendor_id')
                                            ->default(Filament::getTenant()->id),
                                        Forms\Components\TextInput::make('nama_kategori')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->placeholder('Masukkan nama kategori')
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->prefix('/')
                                            ->disabled()
                                            ->dehydrated()
                                            ->helperText('Slug akan terisi otomatis'),
                                    ])
                                    ->editOptionForm([
                                        Forms\Components\Hidden::make('vendor_id')
                                            ->default(Filament::getTenant()->id),
                                        Forms\Components\TextInput::make('nama_kategori')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->placeholder('Masukkan nama kategori')
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->prefix('/')
                                            ->disabled()
                                            ->dehydrated()
                                            ->helperText('Slug akan terisi otomatis'),
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('harga_dasar')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->live(onBlur: true),
                            ]),
                    ])->columnSpanFull(),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Deskripsi')
                            ->description('Tambahkan deskripsi lengkap produk')
                            ->icon('heroicon-o-document-text')
                            ->collapsible()
                            ->schema([
                                Forms\Components\RichEditor::make('deskripsi')
                                    ->required()
                                    ->columnSpanFull()
                                    ->placeholder('Tuliskan deskripsi produk disini...')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                        'undo',
                                        'redo',
                                    ]),
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
                    ->limit(3)
                    ->defaultImageUrl(url('images/placeholder.jpg'))
                    ->ring(2)
                    ->overlap(4),
                Tables\Columns\TextColumn::make('nama_produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg')
                    ->description(fn(Produk $record): string => Str::limit(strip_tags($record->deskripsi), 50))
                    ->wrap()
                    ->copyable()
                    ->copyMessage('Nama produk disalin')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->icon('heroicon-m-tag')
                    ->color('primary')
                    ->tooltip('Kategori Produk'),
                Tables\Columns\TextColumn::make('spesifikasiProduk')
                    ->label('Spesifikasi')
                    ->formatStateUsing(function ($record) {
                        $specs = [];
                        $record->spesifikasiProduk->each(function ($spek) use (&$specs) {
                            $specs[] = "{$spek->spesifikasi->nama_spesifikasi}: " . (is_array($spek->pilihan) ? implode(', ', $spek->pilihan) : $spek->pilihan);
                        });
                        return implode(' | ', $specs);
                    })
                    ->wrap()
                    ->searchable()
                    ->tooltip('Spesifikasi Produk')
                    ->copyable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat pada')
                    ->toggleable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('d M Y H:i:s')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Diubah pada')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('d M Y H:i:s')),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_id')
                    ->relationship('kategori', 'nama_kategori')
                    ->label('Filter Kategori')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari')
                            ->placeholder('Select date')
                            ->closeOnDateSelection()
                            ->native(false),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai')
                            ->placeholder('Select date')
                            ->closeOnDateSelection()
                            ->native(false),
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
                    })
                    ->columns(2),
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
                                    'kategori'
                                ])
                                ->first();

                            // Pass the model directly instead of converting to array
                            $pdf = Pdf::loadView('pdf.produk', [
                                'produk' => $produk
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalDescription('Are you sure you want to delete these products? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('No, cancel')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Products deleted')
                                ->body('The selected products have been deleted successfully.')
                        ),
                ])
                    ->tooltip('Bulk Actions')
                    ->color('gray')
                    ->icon('heroicon-m-chevron-down'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SpesifikasiProdukRelationManager::class,
            // WholesalePriceRelationManager::class,
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
