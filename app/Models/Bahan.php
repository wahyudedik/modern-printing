<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class Bahan extends BaseModel
{
    protected $table = 'bahans';

    protected $fillable = [
        'vendor_id',
        'nama_bahan',
        'hpp',
        'satuan',
        'stok'
    ];

    protected $casts = [
        'hpp' => 'decimal:2',
        'stok' => 'string'
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function wholesalePrice()
    {
        return $this->hasMany(WholesalePrice::class, 'bahan_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'bahan_id');
    }

    public function spesifikasiProduk()
    {
        return $this->belongsToMany(SpesifikasiProduk::class, 'spesifikasi_produk_bahans', 'bahan_id', 'spesifikasi_produk_id');
    }

    public function checkStockLevel()
    {
        if ($this->stok <= 10) {
            Notification::make()
                ->warning()
                ->title('Low Stock Alert')
                ->icon('heroicon-o-exclamation-triangle')
                ->body("Stock for {$this->nama_bahan} is running low ({$this->stok} remaining)")
                ->actions([
                    Action::make('restock')
                        ->button()
                        ->url(route('filament.app.resources.bahans.edit', [
                            'tenant' => $this->vendor->slug,
                            'record' => $this->id
                        ]))
                        ->color('warning')
                ])
                ->persistent()
                ->sendToDatabase($this->vendor->members);
        }
    }
}
