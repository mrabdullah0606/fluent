<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\TeacherVerifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

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

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => Carbon::now()->addMinutes(15), // Code expires in 15 minutes
            'is_verified' => false,
        ]);

        // Send verification email
        Mail::to($user->email)->send(new TeacherVerifyMail([
            'title' => 'Email Verification Required',
            'body' => 'Your verification code is: ' . $verificationCode,
            'code' => $verificationCode,
            'name' => $user->name,
        ]));

        // Store user ID in session for verification
        session(['verification_user_id' => $user->id]);

        return redirect()->route('teacher.verify.form')->with('message', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Show verification form.
     */
    public function showVerifyForm()
    {
        if (!session('verification_user_id')) {
            return redirect()->route('teacher.register')->with('error', 'Please register first.');
        }

        return view('auth.teacher.verify');
    }

    /**
     * Handle code verification.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('teacher.register')->with('error', 'Please register first.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('teacher.register')->with('error', 'User not found.');
        }

        // Check if code has expired
        if (Carbon::now()->isAfter($user->verification_code_expires_at)) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        // Check if code matches
        if ($user->verification_code !== $request->verification_code) {
            return back()->with('error', 'Invalid verification code.');
        }

        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        // Log the user in
        Auth::login($user);

        // Clear session
        session()->forget('verification_user_id');

        return redirect()->route('teacher.dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend verification code.
     */
    public function resendCode()
    {
        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('teacher.register')->with('error', 'Please register first.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('teacher.register')->with('error', 'User not found.');
        }

        // Generate new verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Send new verification email
        Mail::to($user->email)->send(new TeacherVerifyMail([
            'title' => 'New Verification Code',
            'body' => 'Your new verification code is: ' . $verificationCode,
            'code' => $verificationCode,
            'name' => $user->name,
        ]));

        return back()->with('success', 'New verification code sent to your email.');
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

        $user = User::where('email', $credentials['email'])->where('role', 'teacher')->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Check if user is verified
        if (!$user->is_verified) {
            session(['verification_user_id' => $user->id]);
            return redirect()->route('teacher.verify.form')->with('error', 'Please verify your email before logging in.');
        }

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
