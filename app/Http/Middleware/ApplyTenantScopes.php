<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorUser;
use Closure;
use PharIo\Manifest\Author;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        VendorUser::addGlobalScope(
            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        return $next($request);
    }
}
