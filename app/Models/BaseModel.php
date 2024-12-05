<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $builder->where('vendor_id', Filament::getTenant()->id);
        });
    }
}
