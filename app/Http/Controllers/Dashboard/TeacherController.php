<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): Response
    {
        $teacher = auth()->user(); // logged-in teacher
        return response()->view('teacher.content.dashboard', compact('teacher'));
    }

    public function editProfile(): View
    {
        $teacher = auth()->user(); // logged-in teacher
        $languages = Language::all();

        return view('teacher.content.profile.edit', compact('teacher', 'languages'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $teacher->name = $request->name;

        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return redirect()->route('teacher.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function publicProfile(): View
    {
        $teacher = auth()->user();
        $languages = Language::all();

        return view('teacher.content.profile.public', compact('teacher', 'languages'));
    }

    public function calendar(): View
    {
        return view('teacher.content.calendar.index');
    }

    public function settings(): View
    {
        return view('teacher.content.profile.settings');
    }

    public function wallet(): View
    {
        return view('teacher.content.wallet.index');
    }
}
