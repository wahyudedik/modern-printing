<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalePrice extends BaseModel
{
    protected $table = 'harga_grosir';

    protected $fillable = [
        'vendor_id',
        'bahan_id',
        'min_quantity',
        'max_quantity',
        'harga'
    ];

    protected $casts = [
        'vendor_id' => 'integer',
        'bahan_id' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'harga' => 'decimal:2'
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function getDiscountedPrice($quantity, $bahanId)
    {
        return $this->where('bahan_id', $bahanId)
            ->where('min_quantity', '<=', $quantity)
            ->where('max_quantity', '>=', $quantity)
            ->first()?->harga ?? null;
    }

    public function calculateFinalPrice($basePrice, $quantity, $bahanId)
    {
        $wholesalePrice = $this->getDiscountedPrice($quantity, $bahanId);
        return $wholesalePrice ?? $basePrice;
    }
}
