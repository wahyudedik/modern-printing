<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimasiProduk extends BaseModel
{
    protected $table = 'estimasi_produks';

    protected $fillable = [
        'vendor_id',
        'produk_id',
        'alat_id',
        'waktu_persiapan',
        'waktu_produksi_per_unit'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}
