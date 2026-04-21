<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role ?? 'warga';

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized - Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
