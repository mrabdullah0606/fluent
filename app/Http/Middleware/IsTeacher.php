<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('teacher.login');
        }

        // Check if user has teacher role
        if (Auth::user()->role !== 'teacher') {
            Auth::logout();
            return redirect()->route('teacher.login')->with('error', 'Unauthorized access.');
        }

        // Check if user is verified
        if (!Auth::user()->is_verified) {
            session(['verification_user_id' => Auth::id()]);
            Auth::logout();
            return redirect()->route('teacher.verify.form')
                ->with('error', 'Please verify your email before accessing this page.');
        }

        return $next($request);
    }
}
