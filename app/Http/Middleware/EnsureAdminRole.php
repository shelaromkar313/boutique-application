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
        // Check API guard (JWT token)
        $user = Auth::guard('api')->user() ?? Auth::guard('web')->user();

        if (! $user || $user->role !== 'admin') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthorized. Administrator access required.',
                    'status' => 403
                ], 403);
            }

            return redirect('/login?role=admin')->withErrors([
                'email' => 'Please log in with an Administrator account to access the Admin Console.'
            ]);
        }

        return $next($request);
    }
}

