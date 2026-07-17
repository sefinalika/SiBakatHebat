<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    /**
     * Hanya admin atau guru yang boleh mengakses (dashboard).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->canViewDashboard()) {
            abort(403, 'Halaman ini hanya untuk admin atau guru.');
        }

        return $next($request);
    }
}
