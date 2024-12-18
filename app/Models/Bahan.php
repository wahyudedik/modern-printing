<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends BaseModel
{
    protected $table = 'bahans';

    protected $fillable = [ 
        'vendor_id',
        'nama_bahan',
        'harga_per_satuan',
        'satuan',
        'stok'
    ];

    protected $casts = [
        'harga_per_satuan' => 'decimal:2',
        'stok' => 'string'
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
}
