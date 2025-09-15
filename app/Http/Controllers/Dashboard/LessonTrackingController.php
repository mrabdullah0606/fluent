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
            ->where('student_id', $studentId)
            ->where('status', 'active')
            // ->where('payment_type', 'package')
            ->get();

        $totalPurchased = $lessonTracking->sum('total_lessons_purchased');
        $lessonsTaken   = $lessonTracking->sum('lessons_taken');
        $remaining      = $lessonTracking->sum('lessons_remaining');
        // dd($meetings, $lessonTracking->toArray(), $totalPurchased, $lessonsTaken, $remaining);
        return view('student.content.zoom.meetings', compact(
            'meetings',
            'totalPurchased',
            'lessonsTaken',
            'remaining',
            'lessonTracking',
        ));
    }

    // public function deductLesson($id)
    // {
    //     try {
    //         $studentId = auth()->id();

    //         $package = UserLessonTracking::with('teacher')
    //             ->where('id', $id)
    //             ->where('student_id', $studentId)
    //             ->first();

    //         if (!$package) {
    //             return response()->json(['error' => 'Package not found'], 404);
    //         }

    //         // ✅ Only notify teacher, do not deduct
    //         $package->teacher->notify(new \App\Notifications\LessonDeductedNotification($package, auth()->user()));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Teacher has been notified to deduct the lesson!'
    //         ]);
    //     } catch (\Throwable $e) {
    //         \Log::error("Error sending lesson deduction notification: " . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return response()->json(['error' => 'Something went wrong.'], 500);
    //     }
    // }
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

            // Send email to teacher
            Mail::to($package->teacher->email)
                ->queue(new LessonDeductionRequestMail($package, $student));

            // (Optional) still notify via Laravel notifications
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

        $notifications = $teacher->unreadNotifications()
            ->where('type', 'App\Notifications\LessonDeductedNotification')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id, // keep ID for marking read
                    'message' => $notif->data['message'],
                    'time' => $notif->created_at->diffForHumans(),
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

        // ✅ Mark all lesson notifications as read
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


    // public function join($id)
    // {
    //     $meeting = ZoomMeeting::with('teacher')->findOrFail($id);

    //     // only apply for package meetings
    //     if ($meeting->meeting_type === 'package') {
    //         $tracking = \DB::table('user_lesson_trackings')
    //             ->where('student_id', auth()->id())
    //             ->where('teacher_id', $meeting->teacher_id)
    //             ->where('status', 'active')
    //             ->first();

    //         if ($tracking) {
    //             \DB::table('user_lesson_trackings')
    //                 ->where('id', $tracking->id)
    //                 ->update([
    //                     'lessons_taken' => $tracking->lessons_taken + 1,
    //                     'lessons_remaining' => $tracking->lessons_remaining - 1,
    //                     'updated_at' => now(),
    //                 ]);
    //         }
    //     }
    //     return redirect()->away($meeting->join_url);
    // }
    public function join($id)
    {
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
        $studentId = auth()->id();

        $pivot = DB::table('zoom_meeting_user')
            ->where('zoom_meeting_id', $meeting->id)
            ->where('user_id', $studentId)
            ->first();

        if ($pivot && !$pivot->has_joined) {

            // ✅ Pick the first package with remaining lessons
            $tracking = DB::table('user_lesson_trackings')
                ->where('student_id', $studentId)
                ->where('teacher_id', $meeting->teacher_id)
                ->where('status', 'active')
                ->where('payment_type', 'package')
                ->where('lessons_remaining', '>', 0) // only deduct if remaining > 0
                ->orderBy('id', 'asc') // oldest package first
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

            // ✅ Mark as joined
            DB::table('zoom_meeting_user')
                ->where('zoom_meeting_id', $meeting->id)
                ->where('user_id', $studentId)
                ->update(['has_joined' => true]);
        }

        return redirect()->away($meeting->join_url);
    }


    // public function join($id)
    // {
    //     $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
    //     if ($meeting->meeting_type === 'package') {
    //         $tracking = \DB::table('user_lesson_trackings')
    //             ->where('student_id', auth()->id())
    //             ->where('teacher_id', $meeting->teacher_id)
    //             ->where('status', 'active')
    //             ->first();

    //         if ($tracking) {
    //             \DB::table('user_lesson_trackings')
    //                 ->where('id', $tracking->id)
    //                 ->update([
    //                     'lessons_taken'     => $tracking->lessons_taken + 1,
    //                     'lessons_remaining' => max($tracking->lessons_remaining - 1, 0), // avoid negative
    //                     'updated_at'        => now(),
    //                 ]);
    //         }
    //     }

    //     if ($meeting->meeting_type === 'duration') {
    //         $tracking = \DB::table('user_lesson_trackings')
    //             ->where('student_id', auth()->id())
    //             ->where('teacher_id', $meeting->teacher_id)
    //             ->where('status', 'active')
    //             ->first();

    //         if ($tracking) {
    //             \DB::table('user_lesson_trackings')
    //                 ->where('id', $tracking->id)
    //                 ->update([
    //                     'lessons_taken'       => $tracking->lessons_taken + 1,
    //                     'lessons_remaining'   => max($tracking->lessons_remaining - 1, 0),
    //                     'updated_at'          => now(),
    //                 ]);
    //         }
    //     }

    //     return redirect()->away($meeting->join_url);
    // }
}
