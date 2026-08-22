<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteLock
{
    /**
     * Handle an incoming request.
     * When SITE_LOCKED=true in .env, all routes redirect to the lockout page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If site is locked and user is NOT already on the lockout page
        if (env('SITE_LOCKED', false) && !$request->is('site-locked')) {
            return redirect()->route('site.locked');
        }

        return $next($request);
    }
}
