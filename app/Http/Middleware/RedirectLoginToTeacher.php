<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLoginToTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        // Redirect only GET requests to /login
        if ($request->is('login') && $request->method() === 'GET') {
            return redirect('/teacher/login');
        }
        // Redirect only GET requests to /register
        if ($request->is('register') && $request->method() === 'GET') {
            return redirect('/teacher/register');
        }
        // Redirect only GET requests to /dashboard
        if ($request->is('dashboard') && $request->method() === 'GET') {
            return redirect('/teacher/dashboard');
        }

        return $next($request);
    }
}
