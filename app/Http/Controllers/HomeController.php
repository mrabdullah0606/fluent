<?php

namespace App\Http\Controllers;

use App\Models\GroupClass;
use App\Models\Language;
use App\Models\LessonPackage;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home page.
     *
     * @return View
     */
    function index(): View
    {
        $languages = Language::withCount('teachers')->get();
        return view('website.content.index', compact('languages'));
    }


    public function showByLanguage($languageId): View
    {
        $language = Language::findOrFail($languageId);

        $teachers = Teacher::with('user')
            ->where('language_id', $languageId)
            ->get();

        return view('website.content.language', compact('language', 'teachers'));
    }

    /**
     * About page.
     *
     * @return View
     */
    public function about(): View
    {
        return view('website.content.about');
    }
    /**
     * Messages page.
     *
     * @return View
     */
    public function messages(): View
    {
        return view('website.content.messages');
    }

    public function calendar(): View
    {
        return view('website.content.calendar');
    }

    /**
     * Contact page.
     *
     * @return View
     */
    public function contact(): View
    {
        return view('website.content.contact');
    }

    /**
     * Find Tutor page.
     *
     * @return View
     * 
     */
    public function findTutor(): View
    {
        return view('website.content.find-tutor');
    }

    public function tutor($id): View
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();

        return view('website.content.tutor', compact('teacher'));
    }

    public function tutorBooking($id): View
    {
        $teacher = User::with([
            'teacherSettings',        // <-- eager load
            'lessonPackages',
            'groupClasses.days',
        ])
            ->where('id', $id)
            ->where('role', 'teacher')
            ->firstOrFail();
        session(['tutor_id' => $teacher->id]); // store in session
        return view('website.content.tutor-booking', compact('teacher'));
    }

    /**
     * Get monthly availability for a teacher.
     */
    public function monthlyAvailability($teacherId, Request $request)
    {
        $year = (int) $request->query('year');
        $month = (int) $request->query('month');

        $teacher = User::where('id', $teacherId)->where('role', 'teacher')->firstOrFail();

        $availabilities = $teacher->getMonthlyAvailability($year, $month);
        // dd($availabilities);
        return response()->json(['availabilities' => $availabilities]);
    }

    /**
     * Get availability slots for a teacher on a specific date.
     */
    public function dateAvailability($teacherId, Request $request)
    {
        $date = $request->query('date'); // Expect 'YYYY-MM-DD'

        $teacher = User::where('id', $teacherId)->where('role', 'teacher')->firstOrFail();

        $slots = $teacher->getAvailabilityForDate($date);
        // dd($slots);
        return response()->json(['slots' => $slots]);
    }

    // public function checkout(Request $request)
    // {
    //     $type = $request->input('type'); // 'duration' or 'package'
    //     $value = $request->input('value'); // duration in mins OR package_id
    //     $price = $request->input('price'); // submitted price (optional fallback)

    //     $summary = '';
    //     $calculatedPrice = (float) $price;

    //     if ($type === 'duration') {
    //         $summary = "{$value}-Minute Session";
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'package') {
    //         $package = LessonPackage::find($value);
    //         $summary = "{$package->number_of_lessons}-Lesson Package";
    //         $calculatedPrice = $package->price;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     }

    //     $total = round($calculatedPrice + $fee, 2);


    //     return view('website.content.checkout', compact('summary', 'calculatedPrice', 'fee', 'total'));
    // }
    public function checkout(Request $request)
    {
        //dd('dd');
        $type = $request->input('type'); // 'duration', 'package', or 'group'
        $value = $request->input('value'); // duration in mins, package_id, or course_id
        $price = $request->input('price'); // fallback price (optional)
        $tutorId = session('tutor_id');
        //dd($tutorId);
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


    // public function tutorBooking($id): View
    // {
    //     // $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
    //     $teacher = User::where('id', $id)
    //         ->where('role', 'teacher')
    //         ->with([
    //             'lessonPackages' => function ($q) {
    //                 $q->where('is_active', true);
    //             },
    //             'teacherSettings',
    //             'groupClasses.days' => function ($q) {
    //                 $q->orderBy('day'); // optional: if you want to show Monday to Sunday
    //             }
    //         ])
    //         ->firstOrFail();
    //     // dd($teacher->toArray());
    //     return view('website.content.tutor-booking', compact('teacher'));
    // }

    /**
     * Find Tutor page.
     *
     * @return View
     * 
     */
    public function oneOnOneTutors(): View
    {
        $teachers = User::with('teacherProfile')->where('role', 'teacher')->get();
        // dd($teachers->toArray());
        return view('website.content.one-to-one', compact('teachers'));
    }

    public function groupLesson(): View
    {
        $courses = GroupClass::with('teacher', 'days')->get();
        $teachers = User::with('teacherProfile')->where('role', 'teacher')->get();
        // dd($courses->toArray());
        return view('website.content.group-lesson', compact('teachers', 'courses'));
    }

    public function adminLogin(): View
    {
        return view('auth.admin.login');
    }
}
