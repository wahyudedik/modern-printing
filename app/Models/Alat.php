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
        'tersedia',
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

    public function estimasiProduk()
    {
        return $this->hasMany(EstimasiProduk::class, 'alat_id');
    }

    public function checkDailyCapacity($requestedTime)
    {
        $totalScheduledTime = Transaksi::whereDate('estimasi_selesai', today())
            ->whereHas('transaksiItem.produk.estimasiProduk', function ($query) {
                $query->where('alat_id', $this->id);
            })->sum('estimated_duration');

        $availableMinutes = $this->kapasitas_cetak_per_jam * 60;
        return ($totalScheduledTime + $requestedTime) <= $availableMinutes;
    }

    public function getNextAvailableSlot()
    {
        $lastScheduledJob = Transaksi::whereHas('transaksiItem.produk.estimasiProduk', function ($query) {
            $query->where('alat_id', $this->id);
        })
            ->orderBy('estimasi_selesai', 'desc')
            ->first();

        return $lastScheduledJob ? $lastScheduledJob->estimasi_selesai : now();
    }
}
