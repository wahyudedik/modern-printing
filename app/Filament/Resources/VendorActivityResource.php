<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VendorActivity;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VendorActivityResource\Pages;
use App\Filament\Resources\VendorActivityResource\RelationManagers;
use Filament\Tables\Grouping\Group;

class VendorActivityResource extends Resource
{
    protected static ?string $model = VendorActivity::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Monitoring';
    protected static ?int $navigationSort = 2;

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->searchable()
                    ->description(fn($record) => $record->created_at->diffForHumans()),

                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-office')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'login' => 'Login',
                        'product_created' => 'Membuat Produk',
                        'product_updated' => 'Update Produk',
                        'equipment_created' => 'Membuat Alat',
                        'equipment_updated' => 'Update Alat',
                        'material_created' => 'Membuat Bahan',
                        'material_updated' => 'Update Bahan',
                        'transaction_created' => 'Transaksi Baru',
                        'transaction_updated' => 'Update Transaksi',
                        default => ucfirst($state)
                    })
                    ->color(fn($state) => match ($state) {
                        'login' => 'success',
                        'product_created', 'product_updated' => 'primary',
                        'equipment_created', 'equipment_updated' => 'warning',
                        'material_created', 'material_updated' => 'info',
                        'transaction_created', 'transaction_updated' => 'purple',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Detail Aktivitas')
                    ->searchable()
                    ->wrap()
                    ->grow()
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->collapsible()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vendor')
                    ->relationship('vendor', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'login' => 'Login',
                        'product_created' => 'Membuat Produk',
                        'product_updated' => 'Update Produk',
                        'equipment_created' => 'Membuat Alat',
                        'equipment_updated' => 'Update Alat',
                        'material_created' => 'Membuat Bahan',
                        'material_updated' => 'Update Bahan',
                        'transaction_created' => 'Transaksi Baru',
                        'transaction_updated' => 'Update Transaksi',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn($q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    })->columns(2)
            ])
            ->poll('10s')
            ->striped()
            ->paginated([25, 50, 100])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVendorActivities::route('/'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('vendor.name')
                    ->label('Vendor')
                    ->icon('heroicon-o-building-office')
                    ->color('primary')
                    ->size(TextEntry\TextEntrySize::Large),

                TextEntry::make('user.name')
                    ->label('User')
                    ->icon('heroicon-o-user')
                    ->color('success'),

                TextEntry::make('action')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'login' => 'Login',
                        'product_created' => 'Membuat Produk',
                        'transaction_created' => 'Transaksi Baru',
                        'material_updated' => 'Update Bahan',
                        default => ucfirst($state)
                    })
                    ->color(fn($state) => match ($state) {
                        'login' => 'success',
                        'product_created' => 'primary',
                        'transaction_created' => 'warning',
                        'material_updated' => 'info',
                        default => 'gray'
                    }),

                TextEntry::make('description')
                    ->label('Detail Aktivitas')
                    ->markdown()
                    ->columnSpanFull(),

                TextEntry::make('changes')
                    ->label('Data Perubahan')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';

                        return collect($state)
                            ->filter() // Remove null values
                            ->map(function ($value, $key) {
                                if (is_array($value)) {
                                    $value = json_encode($value, JSON_PRETTY_PRINT);
                                }
                                return "**{$key}:** {$value}";
                            })
                            ->join("\n\n");
                    })
                    ->markdown()
                    ->prose()
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->helperText(fn($record) => $record->created_at->diffForHumans())
            ]);
    }
}
