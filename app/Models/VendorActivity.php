<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorActivity extends Model
{
    protected $table = 'vendor_activities';

    protected $fillable = [
        'vendor_id',
        'user_id',
        'action',
        'description',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
