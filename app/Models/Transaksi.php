<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends BaseModel
{
    protected $table = 'transaksis';

    protected $fillable = [
        'vendor_id',
        'kode',
        'minimal_qty',
        'total_qty',
        'total_harga',
        'metode_pembayaran',
        'status'
    ];

    protected $casts = [
        'minimal_qty' => 'integer', 
        'total_qty' => 'integer',
        'total_harga' => 'decimal:2',
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

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'transaksi_produk', 'transaksi_id', 'produk_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class, 'transaksi_pelanggan', 'transaksi_id', 'pelanggan_id')
            ->withTimestamps();
    }
}
