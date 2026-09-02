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

            return redirect('/estilo-hq-console/login')->withErrors([
                'email' => 'Restricted Area: Please authenticate with your Administrator credentials.'
            ]);
        }

        if (! $user->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthorized. Administrator access required.',
                    'status' => 403
                ], 403);
            }

            return redirect('/estilo-hq-console/login')->withErrors([
                'email' => 'Access Denied: Current account lacks Administrator privileges.'
            ]);
        }

        return $next($request);
    }
}

