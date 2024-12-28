<?php

namespace App\Filament\App\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PelangganResource\Pages;
use App\Filament\App\Resources\PelangganResource\RelationManagers;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $label = 'Pelanggan';

    protected static ?string $pluralLabel = 'Pelanggan';

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?string $title = 'Pelanggan';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'pelanggan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'carbon-customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->description('Masukkan informasi pelanggan dengan lengkap')
                    ->icon('heroicon-o-user-circle')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('kode')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->default(function () {
                                        return 'PLG-' . now()->format('YmdHis') . '-' . Auth::user()->id;
                                    })
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder('Kode pelanggan akan dibuat otomatis')
                                    ->prefixIcon('heroicon-m-identification')
                                    ->helperText('Kode akan generate otomatis oleh sistem'),
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->placeholder('Masukkan nama pelanggan')
                                    ->prefixIcon('heroicon-m-user')
                                    ->helperText('Masukkan nama lengkap pelanggan'),
                                Forms\Components\Textarea::make('alamat')
                                    ->required()
                                    ->placeholder('Masukkan alamat lengkap')
                                    ->helperText('Masukkan alamat lengkap termasuk kode pos')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('no_telp')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->placeholder('Masukkan nomor telepon')
                                    ->prefixIcon('heroicon-m-phone')
                                    ->helperText('Contoh: 081234567890')
                                    ->mask('9999-9999-9999'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->placeholder('Masukkan email')
                                    ->prefixIcon('heroicon-m-envelope')
                                    ->helperText('Contoh: nama@domain.com')
                                    ->suffixIcon(fn($state) => $state ? 'heroicon-m-check-circle' : null)
                                    ->suffixIconColor('success'),
                                Forms\Components\Placeholder::make('transaksi_terakhir')
                                    ->label('Transaksi Terakhir')
                                    ->content(fn($record) => $record?->transaksi_terakhir ? $record->transaksi_terakhir->format('d F Y H:i:s') : '-'),
                            ])->columns(2),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode pelanggan berhasil disalin')
                    ->copyMessageDuration(1500)
                    ->weight('bold')
                    ->color('primary')
                    ->tooltip('Klik untuk menyalin kode'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->weight('medium')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->wrap()
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    ->tooltip(fn($record) => $record->alamat),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->copyable()
                    ->copyMessage('Nomor telepon berhasil disalin'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->iconColor('info')
                    ->copyable()
                    ->copyMessage('Email berhasil disalin'),
                Tables\Columns\TextColumn::make('transaksi_terakhir')
                    ->label('Transaksi Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-clock')
                    ->iconColor('warning'),
            ])->defaultSort('transaksi_terakhir', 'desc')
            ->filters([
                Tables\Filters\Filter::make('transaksi_terakhir')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y')
                            ->prefixIcon('heroicon-m-calendar')
                            ->suffixIcon('heroicon-m-chevron-down'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y')
                            ->prefixIcon('heroicon-m-calendar')
                            ->suffixIcon('heroicon-m-chevron-down'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaksi_terakhir', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaksi_terakhir', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'Dari ' . Carbon::parse($data['from'])->format('d/m/Y');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Sampai ' . Carbon::parse($data['until'])->format('d/m/Y');
                        }
                        return $indicators;
                    })
                    ->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-m-eye')
                        ->tooltip('Lihat Detail')
                        ->modalWidth('5xl')
                        ->slideOver(),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square')
                        ->tooltip('Edit Data')
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->tooltip('Hapus Data')
                        ->modalAlignment('center'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->tooltip('Hapus Data Terpilih')
                        ->modalAlignment('center')
                        ->modalDescription('Apakah anda yakin ingin menghapus data yang dipilih? Data yang sudah dihapus tidak dapat dikembalikan.')
                        ->modalHeading('Hapus Data Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
