<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends BaseModel
{
    protected $table = 'transaksi_items';

    protected $fillable = [
        'vendor_id',
        'transaksi_id',
        'produk_id',
        'bahan_id',
        'kuantitas',
        'harga_satuan',
        'spesifikasi'
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_satuan' => 'decimal:2',
        'spesifikasi' => 'json'
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    protected static function booted()
    {
        static::created(function ($transaksiItem) {
            $bahan = Bahan::find($transaksiItem->bahan_id);
            if ($bahan) {
                $bahan->decrement('stok', $transaksiItem->kuantitas);
                $bahan->checkStockLevel(); // Trigger notifikasi stok rendah jika diperlukan
            }
        });
    }

}
