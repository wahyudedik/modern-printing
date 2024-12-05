<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorUser extends Model
{
    protected $table = 'vendor_users';

    protected $fillable = [
        'vendor_id',
        'user_id',
    ]; 

    public function vendor()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_id');
    }

    public function user() 
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
}
