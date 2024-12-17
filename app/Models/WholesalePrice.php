<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalePrice extends BaseModel
{
    protected $table = 'wholesale_prices';

    protected $fillable = [
        'vendor_id',
        'bahan_id',
        'min_quantity',
        'max_quantity',
        'price_per_unit'
    ];

    protected $casts = [
        'vendor_id' => 'integer',
        'bahan_id' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'price_per_unit' => 'decimal:2'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}
