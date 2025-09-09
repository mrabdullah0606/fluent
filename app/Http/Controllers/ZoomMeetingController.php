<?php

namespace App\Http\Controllers;

use App\Mail\ZoomMeetingInvitationMail;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ZoomMeetingController extends Controller
{
    public function index(Request $request)
    {
        $meetings = ZoomMeeting::latest()
            ->paginate(10);

        return view('teacher.content.zoom.meetings', compact('meetings'));
    }

    // public function getSummaries(Request $request)
    // {
    //     $type = $request->input('type');

    //     $summaries = \DB::table('payments')
    //         ->where('teacher_id', auth()->id())
    //         ->where('status', 'successful')
    //         ->where('type', $type)
    //         ->pluck('summary')
    //         ->unique()
    //         ->values();

    //     return response()->json($summaries);
    // }

    // public function getStudentsBySummary(Request $request)
    // {
    //     $summary = $request->input('summary');

    //     $students = \App\Models\User::whereHas('payments', function ($q) use ($summary) {
    //         $q->where('teacher_id', auth()->id())
    //             ->where('status', 'successful')
    //             ->where('summary', $summary);
    //     })->get(['id', 'name', 'email']);

    //     return response()->json($students);
    // }
    public function getSummaries(Request $request)
    {
        $type = $request->input('type'); // package | duration | group

        $summaries = DB::table('user_lesson_trackings')
            ->where('teacher_id', auth()->id())
            ->where('payment_type', $type) // ✅ matches your column
            ->pluck('package_summary')     // ✅ matches your column
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
            ->where('user_lesson_trackings.lessons_remaining', '>', 0) // ✅ sirf active lessons
            ->select('users.id', 'users.name', 'users.email', 'user_lesson_trackings.id as tracking_id')
            ->distinct()
            ->get();

        return response()->json($students);
    }



    // public function store(Request $request, ZoomService $zoomService)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'topic' => 'required|string|max:255',
    //         'start_time' => 'required|date|after:now',
    //         'duration' => 'required|integer|min:1|max:480',
    //         'meeting_type' => 'required',
    //         'attendees' => 'array', // <-- NEW
    //         // 'attendees.*' => 'email', // each attendee must be email
    //         'attendees.*' => 'string', // <-- was 'email', now string to allow id:trackingId

    //     ]);

    //     try {
    //         $zoomHostEmail = config('services.zoom.host_email', 'itsabdullah824@gmail.com');

    //         $zoomData = $zoomService->createMeeting(
    //             $zoomHostEmail,
    //             $validated['topic'],
    //             $validated['start_time'],
    //             $validated['duration']
    //         );

    //         if (!$zoomData) {
    //             return back()->with('error', 'Failed to create Zoom meeting.');
    //         }

    //         $zoomMeeting = ZoomMeeting::create([
    //             'uuid' => $zoomData['uuid'] ?? null,
    //             'meeting_id' => $zoomData['id'],
    //             'host_id' => $zoomData['host_id'] ?? $zoomHostEmail,
    //             'topic' => $zoomData['topic'],
    //             'start_time' => $zoomData['start_time'],
    //             'duration' => $zoomData['duration'],
    //             'teacher_id' => auth()->user()->id,
    //             'meeting_type' => $request->input('meeting_type'),
    //             'timezone' => $zoomData['timezone'] ?? 'Asia/Karachi',
    //             'join_url' => $zoomData['join_url'],
    //             'start_url' => $zoomData['start_url'] ?? null,
    //             'password' => $zoomData['password'] ?? null,
    //             'raw_response' => $zoomData,
    //         ]);

    //         // if ($request->has('attendees')) {
    //         //     foreach ($request->attendees as $email) {
    //         //         $student = \App\Models\User::where('email', $email)->first();
    //         //         if ($student) {
    //         //             // save in pivot table
    //         //             $zoomMeeting->attendees()->attach($student->id, [
    //         //                 'teacher_id' => auth()->id(),
    //         //             ]);

    //         //             // send mail
    //         //             Mail::to($email)->queue(new ZoomMeetingInvitationMail($zoomMeeting));
    //         //         }
    //         //     }
    //         // }
    //         if ($request->has('attendees')) {
    //             foreach ($request->attendees as $attendee) {
    //                 [$studentId, $trackingId] = explode(':', $attendee);

    //                 $zoomMeeting->attendees()->attach($studentId, [
    //                     'teacher_id' => auth()->id(),
    //                     'lesson_tracking_id' => $trackingId,
    //                 ]);

    //                 // optional: send mail
    //                 $student = \App\Models\User::find($studentId);
    //                 if ($student) {
    //                     Mail::to($student->email)->queue(new ZoomMeetingInvitationMail($zoomMeeting));
    //                 }
    //             }
    //         }



    //         return redirect()->route('teacher.zoom.meetings.index')
    //             ->with('success', 'Zoom meeting created and emails sent successfully!');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'An error occurred while creating the meeting. Please try again.');
    //     }
    // }

    /**
     * Delete a meeting (optional method for future use)
     */
public function store(Request $request, ZoomService $zoomService)
{
    // Validate the request
    $validated = $request->validate([
        'topic' => 'required|string|max:255',
        'start_time' => 'required|date|after:now',
        'duration' => 'required|integer|min:1|max:480',
        'meeting_type' => 'required',
        'attendees' => 'array',
        'attendees.*' => 'string', // format: studentId:trackingId
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

        // Attach attendees to meeting and send emails
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

        // ✅ Teacher deducts lessons for students who sent notifications
        $teacher = auth()->user();
        $notifications = $teacher->unreadNotifications()
            ->where('type', 'App\Notifications\LessonDeductedNotification');

        foreach ($notifications as $notif) {
            $trackingId = $notif->data['tracking_id'] ?? null; // ensure tracking ID exists
            if (!$trackingId) continue;

            $package = UserLessonTracking::find($trackingId);
            if ($package && $package->lessons_remaining > 0) {
                $package->lessons_taken += 1;
                $package->lessons_remaining -= 1;
                $package->save();
            }

            // mark notification as read
            $notif->markAsRead();
        }

        return redirect()->route('teacher.zoom.meetings.index')
            ->with('success', 'Zoom meeting created, notifications processed, and lessons deducted!');
    } catch (\Exception $e) {
        Log::error('Zoom meeting creation failed: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        return back()->with('error', 'An error occurred while creating the meeting. Please try again.');
    }
}


    public function destroy(ZoomMeeting $meeting)
    {
        // You can implement Zoom API call to delete the meeting here if needed
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
