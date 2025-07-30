<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Middleware to ensure the user is logged in.
 *
 * This middleware checks if the user is logged in by verifying the session.
 * If the user is not logged in, they are redirected to the login page.
 * It allows access to the login and login submit routes without requiring a session.
 */


class EnsureLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Middleware EnsureLoggedIn dijalankan pada URL: ' . $request->path());

        if ($request->is('login') || $request->is('login-submit')) {
            return $next($request);
        }

        if (!session()->has('user')) {
            Log::warning('User tidak login, redirect ke login.');
            return redirect()->route('login');
        }

        return $next($request);
    }

}


