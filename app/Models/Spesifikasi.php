<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spesifikasi extends Model
{
    protected $table = 'spesifikasis';

    protected $fillable = [
        'vendor_id',
        'nama_spesifikasi',
        'tipe_input',
        'satuan'
    ];

    protected $casts = [
        'nama_spesifikasi' => 'string',
        'tipe_input' => 'string',
        'satuan' => 'string'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function spesifikasiProduk()
    {
        return $this->hasMany(SpesifikasiProduk::class, 'spesifikasi_id');
    }
}
