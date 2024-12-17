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
        'quantity',
        'price',
        'wholesale_price_per_unit',
        'specs'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'wholesale_price_per_unit' => 'decimal:2',
        'specs' => 'json'
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
}
