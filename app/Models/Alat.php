<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends BaseModel
{
    protected $table = 'alats';

    protected $fillable = [
        'vendor_id',
        'nama_alat',
        'merk',
        'model',
        'spesifikasi',
        'status',
        'tanggal_pembelian',
        'kapasitas_cetak_per_jam',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'kapasitas_cetak_per_jam' => 'integer',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'aktif' => 'success',
            'maintenance' => 'warning',
            'rusak' => 'danger',
        ][$this->status] ?? 'secondary';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeRusak($query)
    {
        return $query->where('status', 'rusak');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'produk_alat');
    }
}
