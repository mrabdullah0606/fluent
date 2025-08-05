<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\BookingRule;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Models\Payment;
use App\Models\GroupClass; // assuming you have this model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeacherController extends Controller
{
    // public function index(): Response
    // {
    //     $teacher = auth()->user(); // logged-in teacher
    //     return response()->view('teacher.content.dashboard', compact('teacher'));
    // }
    public function index(): Response
    {
        $teacher = auth()->user();

        // Get all Zoom meetings by this teacher, ordered by start_time
        $zoomMeetings = ZoomMeeting::with('group')
            ->where('teacher_id', $teacher->id)
            ->orderBy('start_time', 'asc')
            ->get();

        // Debug: Check if meetings exist
        \Log::info('Zoom Meetings Count: ' . $zoomMeetings->count());

        // Prepare meeting details array
        $meetingDetails = [];

        foreach ($zoomMeetings as $meeting) {
            \Log::info('Processing meeting: ' . $meeting->topic);

            // Check if we have payment records or just show all meetings
            $payments = Payment::where('teacher_id', $teacher->id)
                ->where('type', $meeting->meeting_type)
                ->get();

            if ($payments->count() > 0) {
                // If payments exist, process with payment logic
                foreach ($payments as $payment) {
                    if ($payment->type === 'duration') {
                        $student = User::find($payment->student_id);
                        $meetingDetails[] = [
                            'meeting_type' => $payment->type,
                            'student_name' => $student->name ?? 'N/A',
                            'topic' => $meeting->topic,
                            'start_time' => $meeting->start_time,
                            'duration' => $meeting->duration,
                            'join_url' => $meeting->join_url,
                        ];
                    } elseif ($payment->type === 'group') {
                        $groupClass = \App\Models\GroupClass::where('teacher_id', auth()->id())->first();
                        $groupName = $groupClass ? $groupClass->title : 'Group Class';
                        $meetingDetails[] = [
                            'meeting_type' => $payment->type,
                            'group_name' => $groupName,
                            'topic' => $meeting->topic,
                            'start_time' => $meeting->start_time,
                            'duration' => $meeting->duration,
                            'join_url' => $meeting->join_url,
                        ];
                    }
                }
            } else {
                // If no payments, just show the meeting (fallback)
                $meetingDetails[] = [
                    'meeting_type' => 'general',
                    'student_name' => 'General Meeting',
                    'topic' => $meeting->topic,
                    'start_time' => $meeting->start_time,
                    'duration' => $meeting->duration,
                    'join_url' => $meeting->join_url,
                ];
            }
        }

        \Log::info('Meeting Details Count: ' . count($meetingDetails));

        // Separate visible and hidden meetings
        $visibleMeetings = array_slice($meetingDetails, 0, 4); // Show first 4
        $hiddenMeetings = array_slice($meetingDetails, 4); // Rest will be hidden

        \Log::info('Visible Meetings: ' . count($visibleMeetings));
        \Log::info('Hidden Meetings: ' . count($hiddenMeetings));

        return response()->view('teacher.content.dashboard', compact('teacher', 'visibleMeetings', 'hiddenMeetings'));
    }

    public function editProfile(): View
    {
        $user = auth()->user(); // contains 'name'
        $teacher = auth()->user()->teacherProfile;
        $languages = Language::all();
        return view('teacher.content.profile.edit', compact('user', 'teacher', 'languages'));
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
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

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

        // ==== Image Upload ====
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('teacher_images', 'public');
            $data['profile_image'] = $imagePath;
        } elseif ($teacher) {
            // Keep old image if no new file
            $data['profile_image'] = $teacher->profile_image;
        }

        if ($teacher) {
            $teacher->update($data);
        } else {
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
        $user = auth()->user();
        $teacher = $user->teacherProfile;

        return view('teacher.content.profile.public', compact('user', 'teacher'));
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
        $rule = BookingRule::firstOrCreate(
            ['teacher_id' => Auth::id()],
            $this->getDefaultBookingRules()
        );

        return view('teacher.content.profile.bookings', compact('rule'));
    }

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

    private function getDefaultBookingRules(): array
    {
        return [
            'min_booking_notice' => '24 hours',
            'booking_window' => '30 days',
            'break_after_lesson' => '15 minutes',
            'accepting_new_students' => true,
        ];
    }

    public function wallet(): View
    {
        return view('teacher.content.wallet.index');
    }
}
