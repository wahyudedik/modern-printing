<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PelangganResource\Pages;
use App\Filament\App\Resources\PelangganResource\RelationManagers;
use Filament\Facades\Filament;

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

    // protected static ?string $navigationGroup = 'Bahan dan Alat';

    protected static ?string $navigationIcon = 'carbon-customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->description('Masukkan informasi pelanggan dengan lengkap')
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
                                    ->placeholder('Kode pelanggan akan dibuat otomatis'),
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->placeholder('Masukkan nama pelanggan'),
                                Forms\Components\Textarea::make('alamat')
                                    ->required()
                                    ->placeholder('Masukkan alamat lengkap')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('no_telp')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->placeholder('Masukkan nomor telepon'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->placeholder('Masukkan email'),
                                Forms\Components\Placeholder::make('transaksi_terakhir')
                                    ->label('Transaksi Terakhir'),
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
                    ->color('primary'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->wrap()
                    ->icon('heroicon-o-map-pin'),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color('success'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->color('info'),
                Tables\Columns\TextColumn::make('transaksi_terakhir')
                    ->label('Transaksi Terakhir')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge()
                    ->color('warning'),
            ])
            ->filters([
                Tables\Filters\Filter::make('transaksi_terakhir')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
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
                    })->columns(2),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                    Tables\Actions\BulkAction::make('update')
                        ->label('Update Selected')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update($data);
                            });
                        })
                        ->form([
                            Forms\Components\Hidden::make('vendor_id')
                                ->default(Filament::getTenant()->id),
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama'),
                            Forms\Components\TextInput::make('alamat')
                                ->label('Alamat'),
                            Forms\Components\TextInput::make('no_telp')
                                ->label('No. Telepon'),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email()

                        ]),
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
