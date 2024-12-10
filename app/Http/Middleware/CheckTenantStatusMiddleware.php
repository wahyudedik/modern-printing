<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Vendor;

class CheckTenantStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $vendor = Vendor::where('id', $user->id)->first();

        if ($vendor?->status === 'inactive') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Account is currently inactive',
                    'status' => 'error'
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('account.inactive')->with('error', 'Your account is currently inactive');
        }

        return $next($request);
    }
}
