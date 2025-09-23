<?php

namespace App\Http\Controllers;

use App\Mail\ZoomMeetingInvitationMail;
use App\Models\LessonAttendance;
use App\Models\UserLessonTracking;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ZoomMeetingController extends Controller
{
    // public function index(Request $request)
    // {
    //     $meetings = ZoomMeeting::latest()
    //         ->paginate(10);

    //     return view('teacher.content.zoom.meetings', compact('meetings'));
    // }
    public function index(Request $request)
    {
        $teacherId = auth()->id();

        // Get meetings with teacher join status
        $meetings = ZoomMeeting::where('zoom_meetings.teacher_id', $teacherId)
            ->leftJoin('zoom_meeting_user as zmu', function ($join) {
                $join->on('zoom_meetings.id', '=', 'zmu.zoom_meeting_id')
                    ->where('zmu.teacher_joined', true);
            })
            ->select('zoom_meetings.*', 'zmu.teacher_joined', 'zmu.teacher_joined_at')
            ->latest('zoom_meetings.created_at')
            ->paginate(10);

        $pendingAttendances = LessonAttendance::with('meeting', 'student')
            ->where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->whereNull('teacher_confirmed_at')
            ->get();

        return view('teacher.content.zoom.meetings', compact('meetings', 'pendingAttendances'));
    }
    // public function index(Request $request)
    // {
    //     $teacherId = auth()->id();

    //     $meetings = ZoomMeeting::where('teacher_id', $teacherId)
    //         ->latest()
    //         ->paginate(10);
    //     $pendingAttendances = LessonAttendance::with('meeting', 'student')
    //         ->where('teacher_id', $teacherId)
    //         ->where('status', 'pending')
    //         ->whereNull('teacher_confirmed_at')
    //         ->get();

    //     return view('teacher.content.zoom.meetings', compact('meetings', 'pendingAttendances'));
    // }

    public function teacherJoin($id)
    {
        $meeting = ZoomMeeting::findOrFail($id);
        $teacherId = auth()->id();

        // Check if this teacher is authorized to join this meeting
        if ($meeting->teacher_id !== $teacherId) {
            return back()->with('error', 'You are not authorized to join this meeting.');
        }

        // Find any student record for this meeting to update teacher_joined status
        DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $meeting->id)
            ->update([
                'teacher_joined' => true,
                'teacher_joined_at' => now(),
                'updated_at' => now(),
            ]);

        // If no student records exist, create a record for teacher tracking
        $existingRecord = DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $meeting->id)
            ->first();

        if (!$existingRecord) {
            // Create a dummy record just for teacher tracking
            // We'll use teacher_id as user_id with a special flag
            DB::table('zoom_meeting_user')->insert([
                'zoom_meeting_id' => $meeting->id,
                'user_id' => $teacherId,
                'teacher_id' => $teacherId,
                'has_joined' => false, // Student hasn't joined
                'teacher_joined' => true, // Teacher has joined
                'teacher_joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Redirect to start URL if available (for hosts), otherwise join URL
        $redirectUrl = $meeting->start_url ?? $meeting->join_url;

        return redirect()->away($redirectUrl);
    }


    public function getSummaries(Request $request)
    {
        $type = $request->input('type');

        $summaries = DB::table('user_lesson_trackings')
            ->where('teacher_id', auth()->id())
            ->where('payment_type', $type)
            ->pluck('package_summary')
            ->unique()
            ->values();

        return response()->json($summaries);
    }

    public function getStudentsBySummary(Request $request)
    {
        $summary = $request->input('summary');
        $teacherId = auth()->id();

        $students = DB::table('user_lesson_trackings')
            ->join('users', 'user_lesson_trackings.student_id', '=', 'users.id')
            ->where('user_lesson_trackings.teacher_id', $teacherId)
            ->where('user_lesson_trackings.package_summary', $summary)
            ->where('user_lesson_trackings.lessons_remaining', '>', 0)
            ->select('users.id', 'users.name', 'users.email', 'user_lesson_trackings.id as tracking_id')
            ->distinct()
            ->get();

        return response()->json($students);
    }

    /**
     * Delete a meeting (optional method for future use)
     */
    public function store(Request $request, ZoomService $zoomService)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:480',
            'meeting_type' => 'required',
            'attendees' => 'array',
            'attendees.*' => 'string',
        ]);

        try {
            $zoomHostEmail = config('services.zoom.host_email', 'itsabdullah824@gmail.com');

            $zoomData = $zoomService->createMeeting(
                $zoomHostEmail,
                $validated['topic'],
                $validated['start_time'],
                $validated['duration']
            );

            if (!$zoomData) {
                return back()->with('error', 'Failed to create Zoom meeting.');
            }

            $zoomMeeting = ZoomMeeting::create([
                'uuid' => $zoomData['uuid'] ?? null,
                'meeting_id' => $zoomData['id'],
                'host_id' => $zoomData['host_id'] ?? $zoomHostEmail,
                'topic' => $zoomData['topic'],
                'start_time' => $zoomData['start_time'],
                'duration' => $zoomData['duration'],
                'teacher_id' => auth()->user()->id,
                'meeting_type' => $request->input('meeting_type'),
                'timezone' => $zoomData['timezone'] ?? 'Asia/Karachi',
                'join_url' => $zoomData['join_url'],
                'start_url' => $zoomData['start_url'] ?? null,
                'password' => $zoomData['password'] ?? null,
                'raw_response' => $zoomData,
            ]);

            if ($request->has('attendees')) {
                foreach ($request->attendees as $attendee) {
                    [$studentId, $trackingId] = explode(':', $attendee);

                    $zoomMeeting->attendees()->attach($studentId, [
                        'teacher_id' => auth()->id(),
                        'lesson_tracking_id' => $trackingId,
                    ]);

                    $student = \App\Models\User::find($studentId);
                    if ($student) {
                        Mail::to($student->email)->queue(new ZoomMeetingInvitationMail($zoomMeeting));
                    }
                }
            }

            $teacher = auth()->user();
            $notifications = $teacher->unreadNotifications()
                ->where('type', 'App\Notifications\LessonDeductedNotification');

            foreach ($notifications as $notif) {
                $trackingId = $notif->data['tracking_id'] ?? null;
                if (!$trackingId) continue;

                $package = UserLessonTracking::find($trackingId);
                if ($package && $package->lessons_remaining > 0) {
                    $package->lessons_taken += 1;
                    $package->lessons_remaining -= 1;
                    $package->save();
                }

                $notif->markAsRead();
            }

            return redirect()->route('teacher.zoom.meetings.index')
                ->with('success', 'Zoom meeting created, notifications processed, and lessons deducted!');
        } catch (\Exception $e) {
            Log::error('Zoom meeting creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'An error occurred while creating the meeting. Please try again.');
        }
    }


    public function destroy(ZoomMeeting $meeting)
    {
        $meeting->delete();

        return redirect()->route('teacher.zoom.meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Get meeting details (optional method for future use)
     */
    public function show(ZoomMeeting $meeting)
    {
        return response()->json([
            'meeting' => $meeting,
            'join_url' => $meeting->join_url,
            'meeting_id' => $meeting->meeting_id,
            'password' => $meeting->password
        ]);
    }
}
