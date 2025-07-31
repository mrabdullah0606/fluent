<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Review;
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

    public function addReviews()
    {
    //$teacher = Teacher::first(); // You can fetch based on logic or selection
    //return view('student.content.reviews.create', compact('teacher'));
    return view('student.content.reviews.create'); // Make sure the file is located at /resources/views/student/add-review.blade.php
    }

    // public function storeReview(Request $request, Teacher $teacher)
    public function storeReview(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|min:10',
        ]);

        $student = Auth::user()->student;

        // Check if the student has already submitted a review for this teacher
        $existingReview = Review::where('teacher_id', $teacher->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already submitted a review for this teacher.');
        }

        Review::create([
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'rating' => $request->rating,
            'comment' => $request->review,
            'is_approved' => false, // Admin will approve
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully and is pending approval.');
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
