<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends BaseModel
{
    protected $table = 'bahans';

    protected $fillable = [ 
        'vendor_id',
        'nama_bahan',
        'deskripsi',
        'spesifikasi',
        'supplier',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'spesifikasi' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'produk_bahan');
    }
}
