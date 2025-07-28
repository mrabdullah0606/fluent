<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherAuthController extends Controller
{
    /**
     * Show teacher registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.teacher.register');
    }

    /**
     * Handle teacher registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        // Create empty teacher profile linked to this user
        $user->teacherProfile()->create([
            'experience' => 0,
            'about_me' => '',
        ]);

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    /**
     * Show teacher login form.
     */
    public function showLoginForm()
    {
        return view('auth.teacher.login');
    }

    /**
     * Handle teacher login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'teacher']), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Logout teacher.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }
}
