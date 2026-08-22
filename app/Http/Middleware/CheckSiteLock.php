<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteLock
{
    /**
     * Handle an incoming request.
     * If the file storage/framework/site_locked exists, block all access.
     * This bypasses config caching issues entirely.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lockFile = storage_path('framework/site_locked');

        // If lock file exists and user is NOT already on the lockout page
        if (file_exists($lockFile) && !$request->is('site-locked')) {
            return redirect()->route('site.locked');
        }

        return $next($request);
    }
}
