<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherSetting;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\BookingRule;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\UserLessonTracking;
use App\Models\Review;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Models\Payment;
use App\Models\GroupClass;
use App\Models\TeacherWallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Index
     *
     * @return Response
     */
    public function index(): Response
    {
        $teacher = auth()->user();

        $wallet = TeacherWallet::where('teacher_id', $teacher->id)->first();

        $zoomMeetings = ZoomMeeting::with('group')
            ->where('teacher_id', $teacher->id)
            ->orderBy('start_time', 'asc')
            ->get();

        $meetingDetails = [];

        foreach ($zoomMeetings as $meeting) {
            $payments = Payment::where('teacher_id', $teacher->id)
                ->where('type', $meeting->meeting_type)
                ->get();

            if ($payments->count() > 0) {
                foreach ($payments as $payment) {
                    if ($payment->type === 'duration' || $payment->type === 'package') {
                        $student = User::find($payment->student_id);
                        $meetingDetails[] = [
                            'meeting_type' => $payment->type,
                            'student_name' => $student->name ?? 'N/A',
                            'topic'        => $meeting->topic,
                            'start_time'   => $meeting->start_time,
                            'duration'     => $meeting->duration,
                            'join_url'     => $meeting->join_url,
                        ];
                    } elseif ($payment->type === 'group') {
                        $groupClass = GroupClass::where('teacher_id', $teacher->id)->first();
                        $groupName  = $groupClass ? $groupClass->title : 'Group Class';

                        $meetingDetails[] = [
                            'meeting_type' => $payment->type,
                            'group_name'   => $groupName,
                            'topic'        => $meeting->topic,
                            'start_time'   => $meeting->start_time,
                            'duration'     => $meeting->duration,
                            'join_url'     => $meeting->join_url,
                        ];
                    }
                }
            } else {
                $meetingDetails[] = [
                    'meeting_type' => 'general',
                    'student_name' => 'General Meeting',
                    'topic'        => $meeting->topic,
                    'start_time'   => $meeting->start_time,
                    'duration'     => $meeting->duration,
                    'join_url'     => $meeting->join_url,
                ];
            }
        }

        $visibleMeetings = array_slice($meetingDetails, 0, 4);
        $hiddenMeetings  = array_slice($meetingDetails, 4);

        $totalEnrollers = Payment::where('teacher_id', $teacher->id)
            ->distinct('student_id')
            ->count('student_id');
        $now         = \Carbon\Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek   = $now->copy()->endOfWeek();

        $lessonsThisWeek = DB::table('zoom_meeting_user')
            ->where('teacher_id', $teacher->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        $lessonSummary = [
            'total_this_week' => $lessonsThisWeek->count(),
            'completed'       => $lessonsThisWeek->where('has_joined', 1)->count(),
            'upcoming'        => $lessonsThisWeek->where('has_joined', 0)->count(),
        ];

        return response()->view('teacher.content.dashboard', compact(
            'teacher',
            'wallet',
            'visibleMeetings',
            'hiddenMeetings',
            'totalEnrollers',
            'lessonSummary'
        ));
    }


    /**
     * Edit Profile
     *
     * @return View
     */
    public function editProfile(): View
    {
        $user = auth()->user();
        $teacher = auth()->user()->teacherProfile;
        $languages = Language::all();
        return view('teacher.content.profile.edit', compact('user', 'teacher', 'languages'));
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'headline'        => 'nullable|string|max:255',
            'about_me'        => 'nullable|string',
            'teaches'         => 'nullable|array',
            'teaches.*'       => 'exists:languages,id',
            'speaks'          => 'nullable|string|max:255',
            'country'         => 'nullable|string|max:255',
            'hobbies'         => 'nullable|string|max:255',
            'certifications'  => 'nullable|string|max:255',
            'experience'      => 'nullable|string|max:255',
            'teaching_style'  => 'nullable|string|max:255',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'intro_video'     => 'nullable|mimetypes:video/mp4,video/x-m4v,video/quicktime,video/webm|max:51200',
        ]);
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        $teacher = $user->teacherProfile;
        $data = [
            'user_id'        => $user->id,
            'headline'       => $request->headline,
            'about_me'       => $request->about_me,
            'teaches'        => $request->teaches,
            'speaks'         => $request->speaks,
            'country'        => $request->country,
            'hobbies'        => $request->hobbies,
            'certifications' => $request->certifications,
            'experience'     => $request->experience,
            'teaching_style' => $request->teaching_style,
        ];
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('teacher_images', 'public');
        } elseif ($teacher) {
            $data['profile_image'] = $teacher->profile_image;
        }
        if ($request->hasFile('intro_video')) {
            $data['intro_video'] = $request->file('intro_video')->store('teacher_videos', 'public');
        } elseif ($teacher) {
            $data['intro_video'] = $teacher->intro_video;
        }
        if ($teacher) {
            $teacher->update($data);
        } else {
            \App\Models\Teacher::create($data);
        }

        return redirect()->back()->with('success', 'Profile saved successfully.');
    }

    /**
     * Public Profile
     *
     * @return View
     */
    public function publicProfile(): View
    {
        $user = auth()->user();
        $teacher = User::with(['teacherProfile', 'lessonPackages'])
            ->where('id', $user->id)
            ->where('role', 'teacher')
            ->firstOrFail();
        $teacherProfile = Teacher::where('user_id', $user->id)->first();
        $introVideo = $teacherProfile?->intro_video;
        $languages = [];
        if (!empty($teacherProfile?->teaches)) {
            $languages = Language::whereIn('id', $teacherProfile->teaches)->pluck('name')->toArray();
        }
        $reviews = collect();
        $reviewsCount = 0;
        $averageRating = 0;
        if ($teacherProfile && $teacherProfile->id) {
            $review = Review::with('student')
                ->where('teacher_id', $teacherProfile->id)
                ->where('is_approved', true)
                ->latest()
                ->get();

            $reviews = $review;
            $reviewsCount = $review->count();
            $averageRating = $reviewsCount > 0 ? round($review->avg('rating'), 1) : 0;
        }
        $duration60Rate = optional($teacher->teacherSettings->firstWhere('key', 'duration_60'))->value ?? 0;
        return view('teacher.content.profile.public', compact(
            'user',
            'teacher',
            'teacherProfile',
            'introVideo',
            'reviews',
            'languages',
            'reviewsCount',
            'averageRating',
            'duration60Rate',
        ));
    }

    /**
     * Calendar
     *
     * @return View
     */
    public function calendar(): View
    {
        return view('teacher.content.calendar.index');
    }

    /**
     * Settings
     *
     * @return View
     */
    public function settings(): View
    {
        return view('teacher.content.profile.settings');
    }

    /**
     * Bookings
     *
     * @return View
     */
    public function bookings(): View
    {
        $rule = BookingRule::firstOrCreate(
            ['teacher_id' => Auth::id()],
            $this->getDefaultBookingRules()
        );

        return view('teacher.content.profile.bookings', compact('rule'));
    }

    /**
     * Update Booking Rules
     *
     * @param Request $request
     * @return void
     */
    public function updateBookingRules(Request $request)
    {
        $validated = $this->validateBookingRules($request);

        $rule = BookingRule::updateOrCreate(
            ['teacher_id' => Auth::id()],
            $validated
        );

        return redirect()
            ->route('teacher.bookings')
            ->with('success', 'Booking rules updated successfully.');
    }

    /**
     * Validate Booking Rules
     *
     * @param Request $request
     * @return array
     */
    private function validateBookingRules(Request $request): array
    {
        $validated = $request->validate([
            'min_booking_notice' => 'required|in:12 hours,24 hours,48 hours',
            'booking_window' => 'required|in:15 days,30 days,60 days',
            'break_after_lesson' => 'required|in:none,15 minutes,30 minutes,60 minutes',
            'accepting_new_students' => 'sometimes|boolean',
        ]);

        $validated['accepting_new_students'] = $request->boolean('accepting_new_students');

        return $validated;
    }

    /**
     * Get Default Booking Rules
     *
     * @return array
     */
    private function getDefaultBookingRules(): array
    {
        return [
            'min_booking_notice' => '24 hours',
            'booking_window' => '30 days',
            'break_after_lesson' => '15 minutes',
            'accepting_new_students' => false,
        ];
    }

    /**
     * Wallet
     *
     * @return View
     */
    public function wallet(): View
    {
        return view('teacher.content.wallet.index');
    }

    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $settings = TeacherSetting::where('teacher_id', $teacher->id)
            ->pluck('value', 'key')
            ->toArray();

        return view('teacher.profile.show', compact('teacher', 'settings'));
    }
}
