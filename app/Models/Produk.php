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
        'kategori_id',
    ];

    protected $casts = [
        'gambar' => 'array',
        'harga_dasar' => 'decimal:2',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function spesifikasiProduk()
    {
        return $this->hasMany(SpesifikasiProduk::class, 'produk_id');
    }

    public function estimasiProduk()
    {
        return $this->hasMany(EstimasiProduk::class, 'produk_id');
    }

    public function getSpesifikasiListAttribute()
    {
        $specs = [];
        $this->spesifikasiProduk->each(function ($spek) use (&$specs) {
            $specs[$spek->spesifikasi->nama_spesifikasi] = [
                'tipe' => $spek->spesifikasi->tipe_input,
                'pilihan' => $spek->pilihan,
                'wajib' => $spek->wajib_diisi
            ];
        });
        return $specs;
    }

    public function getEstimatedProductionTime($quantity)
    {
        $totalTime = 0;

        // Calculate time for each equipment process
        $this->estimasiProduk->each(function ($estimasi) use (&$totalTime, $quantity) {
            $totalTime += $estimasi->calculateTotalProductionTime($quantity);
        });

        return $totalTime;
    }
}
