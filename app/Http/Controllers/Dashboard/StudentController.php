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

class StudentController extends Controller
{
    public function index(): Response
    {
        $student = auth()->user(); // logged-in student
        return response()->view('student.content.dashboard', compact('student'));
    }

    public function editProfile(): View
    {
        $student = auth()->user(); // logged-in student
        $languages = Language::all();

        return view('student.content.profile.edit', compact('student', 'languages'));
    }

    public function updateProfile(Request $request)
    {
        $student = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $student->name = $request->name;

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    function publicProfile(): View
    {
        $student = auth()->user();
        $languages = Language::all();

        return view('student.content.profile.public', compact('student', 'languages'));
    }
}
