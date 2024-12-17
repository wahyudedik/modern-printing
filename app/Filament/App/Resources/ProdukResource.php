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
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('kategori')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])->columnSpanFull(),
                Forms\Components\Group::make()
                    ->schema([
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
                    ->size('lg')
                    ->description(fn(Produk $record): string => strip_tags($record->deskripsi)),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->icon('heroicon-m-tag')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
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
                                    'vendor'
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
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SpesifikasiProdukRelationManager::class,
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
