<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Add cache control headers to prevent browser back-button vulnerability.
 * Prevents sensitive pages (profile, orders, checkout) from being cached.
 */
class SecurePageCacheControl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add headers to prevent browser caching of sensitive pages
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');

        return $response;
    }
}
