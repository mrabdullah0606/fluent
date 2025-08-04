<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use App\Models\GroupClass;
use App\Models\ZoomMeeting;
use App\Models\Payment;
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
    $student = auth()->user();

    // Get all payments made by this student
    $payments = Payment::where('student_id', $student->id)->get();

    $meetingDetails = [];

    foreach ($payments as $payment) {
        // Fetch the meetings created by the same teacher and with same meeting_type
        $zoomMeetings = ZoomMeeting::where('teacher_id', $payment->teacher_id)
            ->where('meeting_type', $payment->type)
            ->get();

        foreach ($zoomMeetings as $meeting) {
            if ($payment->type === 'duration') {
                $meetingDetails[] = [
                    'meeting_type' => $payment->type,
                    'teacher_name' => $meeting->teacher->name ?? 'Unknown Teacher',
                    'topic' => $meeting->topic,
                    'start_time' => $meeting->start_time,
                    'duration' => $meeting->duration,
                    'join_url' => $meeting->join_url,
                ];
            }

            if ($payment->type === 'group') {
                // Get the group class title based on teacher_id
                $groupClass = \App\Models\GroupClass::where('teacher_id', $payment->teacher_id)->first();
                $groupName = $groupClass ? $groupClass->title : 'Group Class';

                $meetingDetails[] = [
                    'meeting_type' => $payment->type,
                    'group_name' => $groupName,
                    'teacher_name' => $meeting->teacher->name ?? 'Unknown Teacher',
                    'topic' => $meeting->topic,
                    'start_time' => $meeting->start_time,
                    'duration' => $meeting->duration,
                    'join_url' => $meeting->join_url,
                ];
            }
        }
    }

    return response()->view('student.content.dashboard', compact('student', 'meetingDetails'));
}


    // public function index(): Response
    // {
    //     $student = auth()->user(); // logged-in student
    //     return response()->view('student.content.dashboard', compact('student'));
    // }

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

     public function checkout(Request $request)
    {
        $type = $request->input('type'); // 'duration', 'package', or 'group'
        $value = $request->input('value'); // duration in mins, package_id, or course_id
        $price = $request->input('price'); // fallback price (optional)

        $summary = '';
        $calculatedPrice = (float) $price;
        $fee = 0;

        if ($type === 'duration') {
            $summary = "{$value}-Minute Session";
            $fee = round($calculatedPrice * 0.03, 2);
        } elseif ($type === 'package') {
            $package = LessonPackage::find($value);
            $summary = "{$package->number_of_lessons}-Lesson Package";
            $calculatedPrice = $package->price;
            $fee = round($calculatedPrice * 0.03, 2);
        } elseif ($type === 'group') {
            // Group course checkout
            $courseId = $value;
            $course = GroupClass::with('teacher')->findOrFail($courseId);

            $summary = "Group Class: {$course->title} by {$course->teacher->name}";
            $calculatedPrice = $course->price_per_student;
            $fee = round($calculatedPrice * 0.03, 2);
        }

        $total = round($calculatedPrice + $fee, 2);

        return view('website.content.checkout', compact('type', 'summary', 'calculatedPrice', 'fee', 'total'));
    }


       public function findTutor(): View
    {
        //dd('test');
        return view('student.content.find-tutor');
    }

     public function oneOnOneTutors(): View
    {
        $teachers = User::with('teacherProfile')->where('role', 'teacher')->get();
        // dd($teachers->toArray());
        return view('student.content.one-to-one', compact('teachers'));
    }

    public function groupLesson(): View
    {
        $courses = GroupClass::with('teacher', 'days')->get();
        $teachers = User::with('teacherProfile')->where('role', 'teacher')->get();
        // dd($courses->toArray());
        return view('student.content.group-lesson', compact('teachers', 'courses'));
    }


}
