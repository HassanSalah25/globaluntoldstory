<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::user()->hasAnyRole(['super-admin', 'admin', 'editor', 'viewer'])) {
            abort(403, 'Access denied. You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
