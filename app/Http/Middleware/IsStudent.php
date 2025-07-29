<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class IsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('student.login');
        }

        // Check if user has student role
        if (Auth::user()->role !== 'student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Unauthorized access.');
        }

        // Check if user is verified
        if (!Auth::user()->is_verified) {
            session(['verification_user_id' => Auth::id()]);
            Auth::logout();
            return redirect()->route('student.verify.form')
                ->with('error', 'Please verify your email before accessing this page.');
        }

        return $next($request);
    }
}
