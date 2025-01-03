<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    // filtering activity
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeByVendor(Builder $query, $vendorId): Builder
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }
}
