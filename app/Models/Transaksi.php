<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends BaseModel
{
    protected $table = 'transaksis';

    protected $fillable = [
        'vendor_id',
        'kode',
        'user_id',
        'pelanggan_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status' => 'string'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }
}
