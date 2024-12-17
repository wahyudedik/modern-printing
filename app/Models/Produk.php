<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends BaseModel
{
    protected $table = 'produks';

    protected $fillable = [
        'vendor_id',
        'gambar',
        'nama_produk',
        'deskripsi',
        'kategori',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'gambar' => 'json',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function spesifikasiProduk()
    {
        return $this->hasMany(SpesifikasiProduk::class, 'produk_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'produk_id');
    }
}
