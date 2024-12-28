<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends BaseModel 
{
    use Notifiable;
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

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pelanggan_id');
    }
}
