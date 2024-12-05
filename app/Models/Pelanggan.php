<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends BaseModel
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'vendor_id',
        'kode',
        'nama',
        'alamat',
        'no_telp',
        'email',
        'transaksi_terakhir'
    ];

    protected $casts = [
        'transaksi_terakhir' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
