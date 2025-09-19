<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\LessonDeductionRequestMail;
use App\Models\UserLessonTracking;
use App\Models\LessonAttendance;
use App\Models\User;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\LessonDeductedNotification;
use Illuminate\Support\Facades\Mail;

class LessonTrackingController extends Controller
{
    public function lessonTracking()
    {
        $studentId = auth()->id();

        $meetings = ZoomMeeting::whereHas('attendees', function ($q) use ($studentId) {
            $q->where('user_id', $studentId);
        })
            ->with(['teacher', 'attendees'])
            ->orderBy('start_time', 'asc')
            ->get();
        $lessonTracking = \DB::table('user_lesson_trackings')
            ->join('users', 'user_lesson_trackings.teacher_id', '=', 'users.id')
            ->where('user_lesson_trackings.student_id', $studentId)
            ->where('user_lesson_trackings.status', 'active')
            ->select('user_lesson_trackings.*', 'users.name as teacher_name')
            ->get();
        $totalPurchased = $lessonTracking->sum('total_lessons_purchased');
        $lessonsTaken   = $lessonTracking->sum('lessons_taken');
        $remaining      = $lessonTracking->sum('lessons_remaining');
        return view('student.content.zoom.meetings', compact(
            'meetings',
            'totalPurchased',
            'lessonsTaken',
            'remaining',
            'lessonTracking',
        ));
    }

    public function deductLesson($id)
    {
        try {
            $student = auth()->user();

            $package = UserLessonTracking::with('teacher')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();

            if (!$package) {
                return response()->json(['error' => 'Package not found'], 404);
            }
            Mail::to($package->teacher->email)
                ->queue(new LessonDeductionRequestMail($package, $student));
            $package->teacher->notify(new \App\Notifications\LessonDeductedNotification($package, $student));

            return response()->json([
                'success' => true,
                'message' => 'Teacher has been notified by email to deduct the lesson!'
            ]);
        } catch (\Throwable $e) {
            \Log::error("Error sending lesson deduction mail: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function unreadLessonNotificationsCount()
    {
        $teacher = auth()->user();

        // $notifications = $teacher->unreadNotifications()
        //     ->where('type', 'App\Notifications\LessonDeductedNotification')
        //     ->latest()
        //     ->take(10)
        //     ->get()
        //     ->map(function ($notif) {
        //         return [
        //             'id' => $notif->id,
        //             'message' => $notif->data['message'],
        //             'time' => $notif->created_at->diffForHumans(),
        //         ];
        //     });
        $notifications = $teacher->unreadNotifications()
            ->where('type', 'App\Notifications\LessonDeductedNotification')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'message' => $notif->data['message'],
                    'time' => $notif->created_at->diffForHumans(),
                    'icon' => $notif->data['icon'] ?? 'bi bi-info-circle text-secondary',
                ];
            });


        return response()->json([
            'unread_count' => $teacher->unreadNotifications()
                ->where('type', 'App\Notifications\LessonDeductedNotification')
                ->count(),
            'notifications' => $notifications,
        ]);
    }

    public function lessonNotificationsPage()
    {
        $teacher = auth()->user();
        $teacher->unreadNotifications
            ->where('type', 'App\Notifications\LessonDeductedNotification')
            ->markAsRead();
        $notifications = $teacher->notifications()->latest()->paginate(20);

        return view('teacher.lesson.notifications', compact('notifications'));
    }

    public function markSingleNotificationRead($id)
    {
        $teacher = auth()->user();
        $notification = $teacher->unreadNotifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications()
            ->where('type', 'App\Notifications\LessonDeductedNotification')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }



    public function join($id)
    {
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
        $studentId = auth()->id();
        $pivot = DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $meeting->id)
            ->where('user_id', $studentId)
            ->first();
        if ($pivot && !$pivot->has_joined) {
            $tracking = DB::table('user_lesson_trackings')
                ->where('student_id', $studentId)
                ->where('teacher_id', $meeting->teacher_id)
                ->where('status', 'active')
                ->where('payment_type', 'package')
                ->where('lessons_remaining', '>', 0)
                ->orderBy('id', 'asc')
                ->first();

            if ($tracking) {
                DB::table('user_lesson_trackings')
                    ->where('id', $tracking->id)
                    ->update([
                        'lessons_taken'     => $tracking->lessons_taken + 1,
                        'lessons_remaining' => max($tracking->lessons_remaining - 1, 0),
                        'updated_at'        => now(),
                    ]);
            }
            DB::table('zoom_meeting_user')
                ->where('zoom_meeting_id', $meeting->id)
                ->where('user_id', $studentId)
                ->update(['has_joined' => true]);
        }
        return redirect()->away($meeting->join_url);
    }

    // public function cancel($id)
    // {
    //     $studentId = auth()->id();

    //     DB::table('zoom_meeting_user')
    //         ->where('zoom_meeting_id', $id)
    //         ->where('user_id', $studentId)
    //         ->update([
    //             'status' => 'cancelled',
    //             'action_by' => $studentId,
    //             'updated_at' => now(),
    //         ]);

    //     return back()->with('success', 'Lesson cancelled successfully.');
    // }

    // Updated cancel method with meeting details
    public function cancel($id)
    {
        $student = auth()->user();

        DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $id)
            ->where('user_id', $student->id)
            ->update([
                'status' => 'cancelled',
                'action_by' => $student->id,
                'updated_at' => now(),
            ]);

        // Get meeting with teacher and all meeting details
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);

        // Try to get the lesson tracking/package info for this student-teacher pair
        $packageInfo = \DB::table('user_lesson_trackings')
            ->where('student_id', $student->id)
            ->where('teacher_id', $meeting->teacher_id)
            ->where('status', 'active')
            ->first();

        // Create package object with meeting and package information
        $packageData = (object)[
            'meeting_id' => $id,
            'meeting_topic' => $meeting->topic ?? $meeting->agenda ?? 'Lesson',
            'meeting_time' => \Carbon\Carbon::parse($meeting->start_time)->format('M d, Y H:i'),
        ];

        // Add package details if found
        if ($packageInfo) {
            $packageData->package_summary = $packageInfo->package_summary ?? "Package with {$meeting->teacher->name}";
            $packageData->package_id = $packageInfo->id;
        }

        // Notify teacher with correct type
        $meeting->teacher->notify(new \App\Notifications\LessonDeductedNotification(
            $packageData,
            $student,
            'cancel'
        ));

        return back()->with('success', 'Lesson cancelled successfully.');
    }

    // Updated reschedule method with meeting details
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'rescheduled_time' => 'required|date|after:now',
        ]);

        $student = auth()->user();

        DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $id)
            ->where('user_id', $student->id)
            ->update([
                'status' => 'rescheduled',
                'rescheduled_time' => $request->rescheduled_time,
                'action_by' => $student->id,
                'updated_at' => now(),
            ]);

        // Get meeting with teacher and all meeting details
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);

        // Try to get the lesson tracking/package info for this student-teacher pair
        $packageInfo = \DB::table('user_lesson_trackings')
            ->where('student_id', $student->id)
            ->where('teacher_id', $meeting->teacher_id)
            ->where('status', 'active')
            ->first();

        // Create package object with meeting and package information
        $packageData = (object)[
            'meeting_id' => $id,
            'meeting_topic' => $meeting->topic ?? $meeting->agenda ?? 'Lesson',
            'meeting_time' => \Carbon\Carbon::parse($meeting->start_time)->format('M d, Y H:i'),
            'rescheduled_time' => \Carbon\Carbon::parse($request->rescheduled_time)->format('M d, Y H:i')
        ];

        // Add package details if found
        if ($packageInfo) {
            $packageData->package_summary = $packageInfo->package_summary ?? "Package with {$meeting->teacher->name}";
            $packageData->package_id = $packageInfo->id;
        }

        // Notify teacher with correct type
        $meeting->teacher->notify(new \App\Notifications\LessonDeductedNotification(
            $packageData,
            $student,
            'reschedule'
        ));

        return back()->with('success', 'Lesson rescheduled successfully.');
    }


    // public function reschedule(Request $request, $id)
    // {
    //     $request->validate([
    //         'rescheduled_time' => 'required|date|after:now',
    //     ]);

    //     $studentId = auth()->id();

    //     DB::table('zoom_meeting_user')
    //         ->where('zoom_meeting_id', $id)
    //         ->where('user_id', $studentId)
    //         ->update([
    //             'status' => 'rescheduled',
    //             'rescheduled_time' => $request->rescheduled_time,
    //             'action_by' => $studentId,
    //             'updated_at' => now(),
    //         ]);

    //     return back()->with('success', 'Lesson rescheduled successfully.');
    // }
    // public function reschedule(Request $request, $id)
    // {
    //     $request->validate([
    //         'rescheduled_time' => 'required|date|after:now',
    //     ]);

    //     $student = auth()->user();

    //     DB::table('zoom_meeting_user')
    //         ->where('zoom_meeting_id', $id)
    //         ->where('user_id', $student->id)
    //         ->update([
    //             'status' => 'rescheduled',
    //             'rescheduled_time' => $request->rescheduled_time,
    //             'action_by' => $student->id,
    //             'updated_at' => now(),
    //         ]);

    //     // Notify teacher
    //     $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
    //     $meeting->teacher->notify(new \App\Notifications\LessonDeductedNotification(
    //         (object)['id' => $id, 'rescheduled_time' => $request->rescheduled_time],
    //         $student
    //     ));

    //     return back()->with('success', 'Lesson rescheduled successfully.');
    // }
}
