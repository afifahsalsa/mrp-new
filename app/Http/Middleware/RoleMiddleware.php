<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::check()) {
            $userRole = auth()->user()->role;
            if (in_array($userRole, $roles)) {
                return $next($request);
            }
            abort(403, 'Unauthorized action.');
        }

        abort(403, 'Unauthorized action.');
    }
}
