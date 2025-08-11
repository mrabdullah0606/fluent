<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Career;
use App\Models\GroupClass;
use App\Models\Language;
use App\Models\LessonPackage;
use App\Models\Review;
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
    // function index(): View
    // {
    //     $languages = Language::withCount('teachers')->get();
    //     return view('website.content.index', compact('languages'));
    // }
    public function index()
    {
        $languages = Language::all()->map(function ($language) {
            $teacherCount = Teacher::whereJsonContains('teaches', (string) $language->id)->count();
            $language->teachers_count = $teacherCount;
            return $language;
        });

        return view('website.content.index', compact('languages'));
    }


    // public function showByLanguage($languageId): View
    // {
    //     $language = Language::findOrFail($languageId);

    //     $teachers = Teacher::with('user')
    //         ->where('language_id', $languageId)
    //         ->get();

    //     return view('website.content.language', compact('language', 'teachers'));
    // }
    public function showByLanguage($languageId): View
    {
        $language = Language::findOrFail($languageId);

        $teachers = Teacher::with('user')
            ->whereJsonContains('teaches', (string) $languageId)
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
        $teacher = User::with('teacherSettings', 'teacherProfile')->where('id', $id)->where('role', 'teacher')->firstOrFail();
        $duration60Rate = optional($teacher->teacherSettings->firstWhere('key', 'duration_60'))->value ?? 0;
        // $reviews = Review::where('teacher_id', $id)->with('student')->get();
        $reviews = Review::with('student')
            ->where('teacher_id', $teacher->id)
            ->where('is_approved', true) // ✅ only approved
            ->latest()
            ->get();
        $reviewsCount = $reviews->count();
        // dd($teacher->toArray(), $duration60Rate);
        // dd($reviews->toArray());
        return view('website.content.tutor', compact('teacher', 'duration60Rate', 'reviews', 'reviewsCount'));
    }

    public function tutorBooking($id): View
    {
        $teacher = User::with([
            'teacherSettings',
            'lessonPackages',
            'groupClasses.days',
            'bookingRules',
        ])
            ->where('id', $id)
            ->where('role', 'teacher')
            ->firstOrFail();
        // dd($teacher->toArray());
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
    //     //dd('dd');
    //     $type = $request->input('type'); // 'duration', 'package', or 'group'
    //     $value = $request->input('value'); // duration in mins, package_id, or course_id
    //     $price = $request->input('price'); // fallback price (optional)
    //     $tutorId = session('tutor_id');
    //     //dd($tutorId);
    //     $summary = '';
    //     $calculatedPrice = (float) $price;
    //     $fee = 0;

    //     if ($type === 'duration') {
    //         $summary = "{$value}-Minute Session";
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'package') {
    //         $package = LessonPackage::find($value);
    //         $summary = "{$package->number_of_lessons}-Lesson Package";
    //         $calculatedPrice = $package->price;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'group') {
    //         // Group course checkout
    //         $courseId = $value;
    //         $course = GroupClass::with('teacher')->findOrFail($courseId);

    //         $summary = "Group Class: {$course->title} by {$course->teacher->name}";
    //         $calculatedPrice = $course->price_per_student;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     }

    //     $total = round($calculatedPrice + $fee, 2);

    //     return view('website.content.checkout', compact('type', 'summary', 'calculatedPrice', 'fee', 'total'));
    // }

    // public function checkout(Request $request)
    // {
    //     $type = $request->input('type'); // 'duration', 'package', or 'group'
    //     $value = $request->input('value'); // duration in mins, package_id, or course_id
    //     $price = $request->input('price'); // discounted price
    //     $originalPrice = $request->input('original_price'); // original price
    //     $discountPercent = $request->input('discount_percent'); // discount %

    //     $tutorId = session('tutor_id');
    //     $summary = '';
    //     $calculatedPrice = (float) $price;
    //     $fee = 0;

    //     if ($type === 'duration') {
    //         $summary = "{$value}-Minute Session";
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'package') {
    //         $package = LessonPackage::find($value);
    //         $summary = "{$package->number_of_lessons}-Lesson Package";
    //         $calculatedPrice = $package->price;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     } elseif ($type === 'group') {
    //         $courseId = $value;
    //         $course = GroupClass::with('teacher')->findOrFail($courseId);

    //         $summary = "Group Class: {$course->title} by {$course->teacher->name}";
    //         $calculatedPrice = $course->price_per_student;
    //         $fee = round($calculatedPrice * 0.03, 2);
    //     }

    //     $total = round($calculatedPrice + $fee, 2);

    //     return view('website.content.checkout', compact(
    //         'type',
    //         'summary',
    //         'calculatedPrice',
    //         'fee',
    //         'total',
    //         'originalPrice',
    //         'discountPercent'
    //     ));
    // }


    /**
     * Find Tutor page.
     *
     * @return View
     * 
     */
    // public function oneOnOneTutors(Request $request): View
    // {
    //     $teacherLanguages = Teacher::select('teaches', 'speaks')->get();
    //     $countries = [
    //         'Pakistan',
    //         'USA',
    //         'UK',
    //         'Canada',
    //         'India',
    //         'France',
    //         'Germany',
    //         'Saudi Arabia',
    //         'UAE',
    //         'Australia',
    //         'Japan',
    //         'China',
    //         'Bangladesh',
    //         'Nepal',
    //         'Turkey',
    //         'South Africa',
    //         'Malaysia',
    //         'Indonesia',
    //         'Italy',
    //         'Spain'
    //     ];
    //     $query = User::with('teacherProfile')->where('role', 'teacher');

    //     if ($request->filled('learn_language')) {
    //         $query->whereHas('teacherProfile', function ($q) use ($request) {
    //             $q->where('teaches', 'LIKE', '%' . $request->learn_language . '%');
    //         });
    //     }

    //     if ($request->filled('speaks')) {
    //         $query->whereHas('teacherProfile', function ($q) use ($request) {
    //             $q->where('speaks', 'LIKE', '%' . $request->speaks . '%');
    //         });
    //     }

    //     if ($request->filled('country')) {
    //         $query->whereHas('teacherProfile', function ($q) use ($request) {
    //             $q->where('country', $request->country);
    //         });
    //     }

    //     if ($request->filled('name')) {
    //         $query->where('name', 'LIKE', '%' . $request->name . '%');
    //     }

    //     if ($request->filled('min_price') && $request->filled('max_price')) {
    //         $query->whereHas('teacherProfile', function ($q) use ($request) {
    //             $q->whereBetween('hourly_rate', [(int)$request->min_price, (int)$request->max_price]);
    //         });
    //     }

    //     $teachers = $query->get();

    //     $languages = Language::all(); // If you have a languages table
    //     dd($teacherLanguages->toArray());
    //     return view('website.content.one-to-one', compact('teachers', 'languages', 'teacherLanguages', 'countries'));
    //     //return view('website.content.one-to-one', compact('teachers', 'languages', 'countries'));
    // }
    public function oneOnOneTutors(Request $request): View
    {
        $countries = [
            'Pakistan',
            'USA',
            'UK',
            'Canada',
            'India',
            'France',
            'Germany',
            'Saudi Arabia',
            'UAE',
            'Australia',
            'Japan',
            'China',
            'Bangladesh',
            'Nepal',
            'Turkey',
            'South Africa',
            'Malaysia',
            'Indonesia',
            'Italy',
            'Spain'
        ];

        $query = User::with('teacherProfile', 'teacherSettings')->where('role', 'teacher');

        if ($request->filled('learn_language')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('teaches', 'LIKE', '%' . $request->learn_language . '%');
            });
        }

        if ($request->filled('speaks')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('speaks', 'LIKE', '%' . $request->speaks . '%');
            });
        }

        if ($request->filled('country')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('country', $request->country);
            });
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->whereBetween('hourly_rate', [(int)$request->min_price, (int)$request->max_price]);
            });
        }

        $teachers = $query->get()->map(function ($teacher) {
            $teacher->teaches_names = [];
            if ($teacher->teacherProfile && $teacher->teacherProfile->teaches) {
                $teachesIds = is_array($teacher->teacherProfile->teaches)
                    ? $teacher->teacherProfile->teaches
                    : json_decode($teacher->teacherProfile->teaches, true);

                if (!empty($teachesIds)) {
                    $teacher->teaches_names = Language::whereIn('id', $teachesIds)->pluck('name')->toArray();
                }
            }
            $teacher->duration_60 = null;
            $settings = $teacher->teacherSettings ?? $teacher->teacher_settings ?? null;
            if ($settings && (is_array($settings) || is_countable($settings)) && count($settings) > 0) {
                foreach ($settings as $setting) {
                    if (!$setting) continue;
                    $key = is_array($setting) ? ($setting['key'] ?? null) : ($setting->key ?? null);
                    $value = is_array($setting) ? ($setting['value'] ?? null) : ($setting->value ?? null);

                    if ($key === 'duration_60') {
                        $teacher->duration_60 = $value;
                        break;
                    }
                }
            }

            return $teacher;
        });
        $languages = Language::all();
        return view('website.content.one-to-one', compact('teachers', 'languages', 'countries'));
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

    public function becomeTutor(): View
    {
        return view('website.content.become-tutor');
    }

    public function careers(): View
    {
        $careers = Career::where('is_active', true)->get();
        // dd($careers->toArray());
        return view('website.content.careers', compact('careers'));
    }

    public function applyForm(Career $career): View
    {
        return view('website.content.apply-form', compact('career'));
    }

    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'career_id' => 'required|exists:careers,id',
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'linkedin' => 'nullable|url',
            'portfolio' => 'nullable|url',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'coverLetter' => 'required|string',
            'whyFit' => 'required|string',
            'expectedSalary' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cv_uploads', 'public');
            $validated['cv_path'] = $cvPath;
        }

        Applicant::create($validated);

        return redirect()->route('careers')->with('success', 'Your application has been submitted successfully.');
    }
    public function applyGeneral(): View
    {
        return view('website.content.apply-general');
    }
}
