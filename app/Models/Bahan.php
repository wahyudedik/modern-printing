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
        'unit_price',
        'unit',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ]; 
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function wholesalePrice()
    {
        return $this->hasMany(WholesalePrice::class, 'bahan_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'bahan_id');
    }

    public function ukuranBahan()
    {
        return $this->hasMany(UkuranBahan::class, 'bahan_id');
    }
}
