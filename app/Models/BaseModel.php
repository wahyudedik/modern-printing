<?php

namespace App\Models;


use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            Log::info('Tenant saat ini:', [
                'tenant' => Filament::getTenant(),
                'user' => Auth::user(),
            ]);

            if (Filament::getTenant()) {
                $builder->where('vendor_id', Filament::getTenant()->id);
            }
        });
    }
}
