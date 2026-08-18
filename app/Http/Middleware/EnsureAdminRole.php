<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Allow the request to proceed only for authenticated admin users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('api')->check() || Auth::guard('api')->user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
