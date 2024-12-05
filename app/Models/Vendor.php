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

}
