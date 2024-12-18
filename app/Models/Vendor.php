<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasAvatar;

class Vendor extends Model implements HasAvatar
{
    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'website',
        'address',
        'phone',
        'logo',
        'status', 
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Active Vendor';
    }

    public function vendorActivities()
    {
        return $this->hasMany(VendorActivity::class, 'vendor_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'vendor_id');
    }

    public function bahan()
    {
        return $this->hasMany(Bahan::class, 'vendor_id');
    }

    public function alat()
    {
        return $this->hasMany(Alat::class, 'vendor_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'vendor_id');
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'vendor_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'vendor_id');
    }

    public function vendor()
    {
        return $this->hasMany(Role::class, 'vendor_id');
    }

    public function wholesalePrice()
    {
        return $this->hasMany(WholesalePrice::class, 'vendor_id');
    }

    public function spesifikasiProduk()
    {
        return $this->hasMany(SpesifikasiProduk::class, 'vendor_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'vendor_id');
    }

    public function kategori()
    {
        return $this->hasMany(KategoriProduk::class, 'vendor_id');
    }

    public function spesifikasi()
    {
        return $this->hasMany(Spesifikasi::class, 'vendor_id');
    }

    public function estimasiProduk()
    {
        return $this->hasMany(EstimasiProduk::class, 'vendor_id');
    }

}
