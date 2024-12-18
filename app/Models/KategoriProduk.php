<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produks';

    protected $fillable = [
        'vendor_id',
        'nama_kategori',
        'slug',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
