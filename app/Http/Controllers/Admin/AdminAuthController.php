<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        // If already logged in and is admin, redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.index');
        }

        return view('auth.admin.login');
    }

    /**
     * Handle admin login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is admin
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.index'));
            }

            // Not admin? Log them out
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. Admins only.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin out and redirect to login page.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
