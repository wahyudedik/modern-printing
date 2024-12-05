<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends BaseModel
{
    protected $table = 'transaksis';

    protected $fillable = [
        'vendor_id',
        'data_pelanggan',
        'data_produk',
        'minimal_qty',
        'total_qty',
        'total_harga',
        'metode_pembayaran',
        'status'
    ];

    protected $casts = [
        'data_pelanggan' => 'array',
        'data_produk' => 'array',
        'minimal_qty' => 'string',
        'total_qty' => 'string',
        'total_harga' => 'string',
        'metode_pembayaran' => 'string',
        'status' => 'string'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger',
        ][$this->status] ?? 'secondary';
    }

    public function getMetodePembayaranLabelAttribute()
    {
        return [
            'cash' => 'Cash',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
        ][$this->metode_pembayaran] ?? '-';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCash($query)
    {
        return $query->where('metode_pembayaran', 'cash');
    }

    public function scopeTransfer($query)
    {
        return $query->where('metode_pembayaran', 'transfer');
    }

    public function scopeQris($query)
    {
        return $query->where('metode_pembayaran', 'qris');
    }
}
