<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLoginToRoleDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('dashboard') && $request->method() === 'GET') {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->role === 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'teacher') {
                    return redirect('/teacher/dashboard');
                } elseif ($user->role === 'student') {
                    return redirect('/student/dashboard');
                }
            }
            return redirect('/student/login');
        }

        if ($request->is('login') && $request->method() === 'GET') {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->role === 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'teacher') {
                    return redirect('/teacher/dashboard');
                } elseif ($user->role === 'student') {
                    return redirect('/student/dashboard');
                }
            }
            return redirect('/student/login');
        }
        if ($request->is('register') && $request->method() === 'GET') {
            return redirect('/teacher/register');
        }
        return $next($request);
    }
}
