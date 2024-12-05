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
        'slug',
        'deskripsi',
        'kategori',
        'harga',
        'diskon',
        'minimal_qty',
        'total_harga'
    ];

    protected $casts = [
        'gambar' => 'array',

    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bahan()
    {
        return $this->belongsToMany(Bahan::class, 'produk_bahan');
    }

    public function alat()
    {
        return $this->belongsToMany(Alat::class, 'produk_alat');
    }
}
