<?php

namespace App\Filament\App\Resources\ProdukResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Bahan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use App\Models\SpesifikasiProduk;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SpesifikasiProdukRelationManager extends RelationManager
{
    protected static string $relationship = 'spesifikasiProduk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Spesifikasi Produk')
                    ->description('Kelola spesifikasi produk Anda')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\hidden::make('vendor_id')
                                    ->default(Filament::getTenant()->id),
                                Select::make('spesifikasi_id')
                                    ->relationship('spesifikasi', 'nama_spesifikasi')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->label('Jenis Spesifikasi')
                                    ->placeholder('Pilih jenis spesifikasi')
                                    ->required(),

                                Select::make('wajib_diisi')
                                    ->options([
                                        '1' => 'Ya',
                                        '0' => 'Tidak'
                                    ])
                                    ->native(false)
                                    ->label('Wajib Diisi?')
                                    ->placeholder('Pilih status')
                                    ->helperText('Apakah spesifikasi ini wajib diisi oleh pembeli?')
                                    ->required(),
                                Select::make('pilihan')
                                    ->multiple()
                                    ->relationship('bahans', 'nama_bahan')
                                    ->options(function () {
                                        return Bahan::where('vendor_id', Filament::getTenant()->id)
                                            ->pluck('nama_bahan', 'id');
                                    })
                                    ->preload()
                                    ->searchable()
                                    ->native(false)
                                    ->label('Pilihan Material')
                                    ->placeholder('Pilih material yang tersedia')
                                    ->helperText('Pilih satu atau lebih material yang tersedia')
                            ])
                            ->columns(2)
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('spesifikasi.nama_spesifikasi')
                    ->label('Nama Spesifikasi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Nama spesifikasi produk')
                    ->wrap(),
                Tables\Columns\IconColumn::make('wajib_diisi')
                    ->label('Wajib Diisi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('bahans.nama_bahan')
                    ->label('Pilihan Material')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->formatStateUsing(fn($state, $record) => $record->bahans->pluck('nama_bahan')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('d M Y H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('d M Y H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('wajib_diisi')
                    ->label('Wajib Diisi')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ])
                    ->placeholder('Semua')
                    ->indicator('Wajib Diisi'),
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
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Dibuat dari ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Dibuat sampai ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    })->columns(2),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Spesifikasi Produk')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->tooltip('Lihat Detail'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->tooltip('Edit Data'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->modalHeading('Hapus Data Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus data yang dipilih? Data yang dihapus tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ]);
    }
}
