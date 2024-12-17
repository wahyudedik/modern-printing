<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpesifikasiProduk extends BaseModel
{
    protected $table = 'spesifikasi_produks';

    protected $fillable = [
        'vendor_id',
        'produk_id',
        'nama',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
