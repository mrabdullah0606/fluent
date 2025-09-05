<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use App\Models\GroupClass;
use App\Models\LessonPackage;
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
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index(): Response
    {
        $student = auth()->user();

        // Get all payments made by this student
        $payments = Payment::where('student_id', $student->id)->get();
        $totalTeachers = $payments->pluck('teacher_id')->unique()->count();
        // dd($totalTeachers);
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
        $upcomingMeetings = collect($meetingDetails)->filter(function ($meeting) {
            return Carbon::parse($meeting['start_time'])->isToday() ||
                Carbon::parse($meeting['start_time'])->isFuture();
        })->values();

        // dd($upcomingMeetings,$meetingDetails);
        return response()->view('student.content.dashboard', compact('student', 'meetingDetails', 'totalTeachers', 'upcomingMeetings'));
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
            // 'date' => 'required|date',
            // 'rating' => 'required|integer|between:1,5',
            // 'review' => 'required|string|min:10',
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
    //     public function publicProfile()
    // {
    //     $student = auth()->user();
    //     return view('student.content.profile.public', compact('student'));
    // }


    public function updateProfile(Request $request)
    {
        $user = auth()->user(); // Single variable for user

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'langauages_i_can_speak' => 'nullable|numeric|min:0',
            'hobbies' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update User table
        $user->name = $request->name;
        $user->save();

        // Get related Student profile
        $studentProfile = $user->studentProfile; // assuming relation: hasOne(Student::class)

        $data = [
            'user_id'                => $user->id,
            'description'            => $request->description,
            'langauages_i_can_speak' => $request->langauages_i_can_speak,
            'hobbies'                => $request->hobbies,
        ];
        // dd($data);

        // Handle Profile Image
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('student_images', 'public');
            $data['profile_image'] = $imagePath;
        } elseif ($studentProfile) {
            // Keep old image if exists
            $data['profile_image'] = $studentProfile->profile_image;
        }

        // Update or Create Student Profile
        if ($studentProfile) {
            $studentProfile->update($data);
        } else {
            \App\Models\Student::create($data);
        }

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    function publicProfile(): View
    {
        $student = auth()->user();
        $languages = Language::all();

        return view('student.content.profile.public', compact('student', 'languages'));
    }

    // public function checkout(Request $request)
    // {
    //     $type = $request->input('type');
    //     $value = $request->input('value');
    //     $price = $request->input('price');

    //     $summary = '';
    //     $calculatedPrice = (float) $price; // Use the passed price (which is discounted)
    //     $originalPrice = (float) $request->input('original_price', 0);
    //     $discountPercent = (int) $request->input('discount_percent', 0);
    //     $fee = 0;

    //     if ($type === 'duration') {
    //         $summary = "{$value}-Minute Session";
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'package') {
    //         $package = LessonPackage::find($value);
    //         $summary = "{$package->number_of_lessons}-Lesson Package";
    //         // Don't override $calculatedPrice - it already contains the discounted price
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'group') {
    //         $courseId = $value;
    //         $course = GroupClass::with('teacher')->findOrFail($courseId);
    //         $summary = "Group Class: {$course->title} by {$course->teacher->name}";
    //         $calculatedPrice = $course->price_per_student;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     }

    //     $total = round($calculatedPrice + $fee, 2);

    //     return view('student.content.checkout', compact(
    //         'type',
    //         'summary',
    //         'calculatedPrice',
    //         'fee',
    //         'total',
    //         'originalPrice',
    //         'discountPercent'
    //     ));
    // }
    public function checkout(Request $request)
    {
        $type = $request->input('type');
        $value = $request->input('value');
        $price = $request->input('price');

        $summary = '';
        $calculatedPrice = (float) $price; // Use the passed price (which is discounted)
        $originalPrice = (float) $request->input('original_price', 0);
        $discountPercent = (int) $request->input('discount_percent', 0);
        $fee = 0;
        $teacherId = null;
        $additionalData = []; // For storing extra package/lesson info

        if ($type === 'duration') {
            $summary = "{$value}-Minute Session";
            $fee = round($calculatedPrice * 0.03, 2);
            $teacherId = $request->input('teacher_id') ?? session('tutor_id');

            // Store slot and date info for duration lessons
            $additionalData = [
                'slot_id' => $request->input('slot_id'),
                'selected_date' => $request->input('selected_date'),
                'duration' => $value
            ];
        } elseif ($type === 'package') {
            $package = LessonPackage::find($value);
            if (!$package) {
                return redirect()->back()->with('error', 'Package not found');
            }

            $summary = "{$package->number_of_lessons}-Lesson Package";
            $fee = round($calculatedPrice * 0.03, 2);
            $teacherId = $package->teacher_id ?? $request->input('teacher_id') ?? session('tutor_id');

            // Store package details
            $additionalData = [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'number_of_lessons' => $package->number_of_lessons,
                'duration_per_lesson' => $package->duration_per_lesson,
                'package_description' => $package->description ?? null
            ];
        } elseif ($type === 'group') {
            $courseId = $value;
            $course = GroupClass::with('teacher')->findOrFail($courseId);
            $summary = "Group Class: {$course->title} by {$course->teacher->name}";
            $calculatedPrice = $course->price_per_student;
            $fee = round($calculatedPrice * 0.03, 2);
            $teacherId = $course->teacher_id;

            // Store group class details
            $additionalData = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'course_description' => $course->description ?? null
            ];
        }

        $total = round($calculatedPrice + $fee, 2);

        // Enhanced session storage with all necessary data
        session([
            'checkout_data' => [
                'type' => $type,
                'value' => $value,
                'teacher_id' => $teacherId,
                'summary' => $summary,
                'calculated_price' => $calculatedPrice,
                'original_price' => $originalPrice,
                'discount_percent' => $discountPercent,
                'fee' => $fee,
                'total' => $total,
                'additional_data' => $additionalData,
                'timestamp' => now()->toISOString() // For session expiry tracking
            ]
        ]);

        return view('student.content.checkout', compact(
            'type',
            'summary',
            'calculatedPrice',
            'fee',
            'total',
            'originalPrice',
            'discountPercent',
            'teacherId',
            'additionalData' // Pass additional data to view if needed
        ));
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


    /* ************************************************************************** */
    /*                                   review                                   */
    /* ************************************************************************** */

    public function reviewStore(Request $request)
{
    $request->validate([
        'teacher_id' => 'required|exists:users,id',
        'rating'     => 'required|integer|min:1|max:5',
        'comment'    => 'required|string|max:1000',
    ]);

    $studentId = Auth::id();

    // Check if student has completed at least one lesson with this teacher
    $hasLesson = \DB::table('user_lesson_trackings')
        ->where('student_id', $studentId)
        ->where('teacher_id', $request->teacher_id)
        ->where('lessons_taken', '>', 0) // ✅ must have taken at least one lesson
        ->exists();

    if (! $hasLesson) {
        return response()->json([
            'error' => 'You can only review a teacher after taking at least one lesson.'
        ], 422);
    }

    // Check if review already exists
    $exists = Review::where('teacher_id', $request->teacher_id)
        ->where('student_id', $studentId)
        ->exists();

    if ($exists) {
        return response()->json([
            'error' => 'You have already reviewed this teacher.'
        ], 422);
    }

    // Store review
    $review = Review::create([
        'teacher_id'  => $request->teacher_id,
        'student_id'  => $studentId,
        'rating'      => $request->rating,
        'comment'     => $request->comment,
        'is_approved' => false
    ]);

    return response()->json([
        'success' => 'Review submitted successfully and is pending approval.',
        'review'  => $review
    ]);
}

    // public function reviewStore(Request $request)
    // {
    //     $request->validate([
    //         'teacher_id' => 'required|exists:users,id',
    //         'rating'     => 'required|integer|min:1|max:5',
    //         'comment'    => 'required|string|max:1000',
    //     ]);

    //     // Check if review already exists
    //     $exists = Review::where('teacher_id', $request->teacher_id)
    //         ->where('student_id', Auth::id())
    //         ->exists();
    //     if ($exists) {
    //         return response()->json(['error' => 'You have already reviewed this teacher.'], 422);
    //     }

    //     $review = Review::create([
    //         'teacher_id' => $request->teacher_id,
    //         'student_id' => Auth::id(),
    //         'rating'     => $request->rating,
    //         'comment'    => $request->comment,
    //         'is_approved' => false
    //     ]);

    //     return response()->json([
    //         'success' => 'Review submitted successfully and is pending approval.',
    //         'review'  => $review
    //     ]);
    // }
}
