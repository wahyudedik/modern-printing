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
        'harga_per_satuan',
        'satuan',
        'stok'
    ];

    protected $casts = [
        'harga_per_satuan' => 'decimal:2',
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
        return $this->belongsToMany(SpesifikasiProduk::class);
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
