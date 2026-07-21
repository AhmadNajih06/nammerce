<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more roles as arguments, e.g. ->middleware('role:admin')
     * or ->middleware('role:admin,pelanggan').
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
