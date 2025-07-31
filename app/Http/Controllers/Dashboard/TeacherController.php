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
    $request->validate([
        'headline'        => 'nullable|string|max:255',
        'about_me'        => 'nullable|string',
        'teaches'         => 'nullable|string|max:255',
        'speaks'          => 'nullable|string|max:255',
        'country'         => 'nullable|string|max:255',
        'rate_per_hour'   => 'nullable|numeric|min:0',
        'hobbies'         => 'nullable|string|max:255',
        'certifications'  => 'nullable|string|max:255',
        'experience'      => 'nullable|string|max:255',
        'teaching_style'  => 'nullable|string|max:255',
    ]);

    $user = auth()->user();

    // Check if teacher profile exists
    $teacher = $user->teacherProfile;

    $data = [
        'user_id'         => $user->id,
        'headline'        => $request->headline,
        'about_me'        => $request->about_me,
        'teaches'         => $request->teaches,
        'speaks'          => $request->speaks,
        'country'         => $request->country,
        'rate_per_hour'   => $request->rate_per_hour,
        'hobbies'         => $request->hobbies,
        'certifications'  => $request->certifications,
        'experience'      => $request->experience,
        'teaching_style'  => $request->teaching_style,
    ];

    if ($teacher) {
        // Update existing teacher profile
        $teacher->update($data);
    } else {
        // Create new teacher profile
        \App\Models\Teacher::create($data);
    }

    return redirect()->back()->with('success', 'Profile saved successfully.');
}


    // public function updateProfile(Request $request)
    // {
    //     $teacher = auth()->user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);
    //     $teacher->name = $request->name;

    //     if ($request->filled('password')) {
    //         $teacher->password = Hash::make($request->password);
    //     }

    //     $teacher->save();

    //     return redirect()->route('teacher.profile.edit')
    //         ->with('success', 'Profile updated successfully.');
    // }

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

    public function bookings(): View
    {
        return view('teacher.content.profile.bookings');
    }

    public function wallet(): View
    {
        return view('teacher.content.wallet.index');
    }
}
