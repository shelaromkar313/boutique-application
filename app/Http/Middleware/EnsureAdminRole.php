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
        $user = Auth::user();

        if (! $user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthenticated.',
                    'message' => 'Please log in to continue.',
                ], 401);
            }

            return redirect('/login?role=admin')->withErrors([
                'email' => 'Please log in with an Administrator account to access the Admin Console.'
            ]);
        }

        if (! $user->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthorized. Administrator access required.',
                    'status' => 403
                ], 403);
            }

            return redirect('/')->withErrors([
                'auth' => 'Access denied. Administrator privileges required.'
            ]);
        }

        return $next($request);
    }
}

