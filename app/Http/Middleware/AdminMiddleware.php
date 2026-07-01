<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Securely authorize only logged-in Admin and Super Admin users
        if (Auth::check() && in_array(strtolower(Auth::user()->role?->role_type), ['admin', 'super_admin'])) {
            return $next($request);
        }

        // Return a secure 403 Forbidden screen to unauthorized users
        abort(403, 'Unauthorized access.');
    }
}