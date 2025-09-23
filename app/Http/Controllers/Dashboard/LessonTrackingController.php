<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\LessonDeductionRequestMail;
use App\Models\UserLessonTracking;
use App\Models\LessonAttendance;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\LessonDeductedNotification;
use Illuminate\Support\Facades\Mail;

class LessonTrackingController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

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
        $pendingAttendances = LessonAttendance::with('meeting', 'teacher')
            ->where('student_id', $studentId)
            ->where('status', 'pending')
            ->whereNull('student_confirmed_at')
            ->get();
        return view('student.content.zoom.meetings', compact(
            'meetings',
            'totalPurchased',
            'lessonsTaken',
            'remaining',
            'lessonTracking',
            'pendingAttendances'
        ));
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
                ->where('payment_type', $meeting->meeting_type)
                ->where('lessons_remaining', '>', 0)
                ->orderBy('id', 'asc')
                ->first();
            if ($tracking) {
                $pricePerLesson = $tracking->price_per_lesson;
                DB::table('user_lesson_trackings')
                    ->where('id', $tracking->id)
                    ->update([
                        'lessons_taken'     => $tracking->lessons_taken + 1,
                        'lessons_remaining' => max($tracking->lessons_remaining - 1, 0),
                        'updated_at'        => now(),
                    ]);
                LessonAttendance::create([
                    'student_id' => $studentId,
                    'teacher_id' => $meeting->teacher_id,
                    'meeting_id' => $meeting->id,
                    'payment_id' => $tracking->payment_id,
                    'base_price' => $pricePerLesson,
                    'status' => 'pending'
                ]);
            }
            DB::table('zoom_meeting_user')
                ->where('zoom_meeting_id', $meeting->id)
                ->where('user_id', $studentId)
                ->update(['has_joined' => true]);
        }
        return redirect()->away($meeting->join_url);
    }

    public function confirmAttendance(Request $request, $attendanceId)
    {
        $request->validate([
            'student_attended' => 'required|boolean',
            'teacher_attended' => 'required|boolean',
        ]);

        try {
            $attendance = LessonAttendance::where('id', $attendanceId)
                ->where('student_id', auth()->id())
                ->where('status', 'pending')
                ->lockForUpdate()
                ->firstOrFail();
            $updateData = [
                'student_attended' => $request->student_attended,
                'student_confirmed_at' => now(),
            ];
            if (is_null($attendance->teacher_confirmed_at)) {
                $updateData['teacher_attended'] = $request->teacher_attended;
            }
            $attendance->update($updateData);
            $bothConfirmed = !is_null($attendance->student_confirmed_at) && !is_null($attendance->teacher_confirmed_at);
            if ($bothConfirmed && $attendance->student_attended && $attendance->teacher_attended && !$attendance->payment_released) {
                $this->processTeacherPayment($attendance);
                $attendance->update([
                    'status' => 'confirmed',
                    'payment_released' => true,
                    'payment_released_at' => now(),
                ]);

                $message = 'Attendance confirmed! Teacher payment has been processed.';
            } else if ($bothConfirmed) {
                $attendance->update(['status' => 'confirmed']);
                $message = 'Attendance confirmation completed by both parties.';
            } else {
                $message = 'Your attendance confirmation recorded. Waiting for teacher confirmation.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error("Error confirming student attendance: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    private function processTeacherPayment(LessonAttendance $attendance)
    {
        try {
            $teacherAmount = $attendance->teacher_earning;
            $adminRetains = $attendance->admin_commission;
            $this->walletService->addEarning(
                $attendance->teacher_id,
                $teacherAmount,
                "Lesson payment for meeting #{$attendance->meeting_id}",
                $attendance->payment_id
            );
            $this->walletService->deductFromAdminWallet(
                $teacherAmount,
                "Teacher payment for lesson - Meeting #{$attendance->meeting_id}",
                $attendance->payment_id,
                $attendance->teacher_id
            );

            \Log::info("Teacher payment processed for lesson", [
                'attendance_id' => $attendance->id,
                'teacher_id' => $attendance->teacher_id,
                'teacher_amount' => $teacherAmount,
                'admin_retains' => $adminRetains
            ]);
        } catch (\Exception $e) {
            \Log::error("Error processing teacher payment: " . $e->getMessage(), [
                'attendance_id' => $attendance->id,
                'teacher_id' => $attendance->teacher_id
            ]);
            throw $e;
        }
    }

    public function getPendingAttendances()
    {
        $studentId = auth()->id();

        $attendances = LessonAttendance::with(['meeting', 'teacher'])
            ->where('student_id', $studentId)
            ->where('status', 'pending')
            ->whereNull('student_confirmed_at')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'meeting_topic' => $attendance->meeting->topic ?? 'Lesson',
                    'teacher_name' => $attendance->teacher->name,
                    'meeting_date' => $attendance->meeting->start_time->format('M d, Y H:i A'),
                    'amount' => $attendance->base_price,
                ];
            });

        return response()->json(['attendances' => $attendances]);
    }

    public function getTeacherPendingAttendances()
    {
        $teacherId = auth()->id();
        $attendances = LessonAttendance::with(['meeting', 'student'])
            ->where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->whereNull('teacher_confirmed_at')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'meeting_topic' => $attendance->meeting->topic ?? 'Lesson',
                    'student_name' => $attendance->student->name,
                    'meeting_date' => $attendance->meeting->start_time->format('M d, Y H:i A'),
                    'amount' => $attendance->base_price,
                ];
            });
        return response()->json(['attendances' => $attendances]);
    }

    public function confirmTeacherAttendance(Request $request, $attendanceId)
    {
        $request->validate([
            'student_attended' => 'required|boolean',
            'teacher_attended' => 'required|boolean',
        ]);

        try {
            $attendance = LessonAttendance::where('id', $attendanceId)
                ->where('teacher_id', auth()->id())
                ->where('status', 'pending')
                ->lockForUpdate()
                ->firstOrFail();
            $updateData = [
                'teacher_attended' => $request->teacher_attended,
                'teacher_confirmed_at' => now(),
            ];
            if (is_null($attendance->student_confirmed_at)) {
                $updateData['student_attended'] = $request->student_attended;
            }
            $attendance->update($updateData);
            $bothConfirmed = !is_null($attendance->student_confirmed_at) && !is_null($attendance->teacher_confirmed_at);
            if ($bothConfirmed && $attendance->student_attended && $attendance->teacher_attended && !$attendance->payment_released) {
                $this->processTeacherPayment($attendance);
                $attendance->update([
                    'status' => 'confirmed',
                    'payment_released' => true,
                    'payment_released_at' => now(),
                ]);
                $message = 'Attendance confirmed! Your payment has been processed.';
            } else if ($bothConfirmed) {
                $attendance->update(['status' => 'confirmed']);
                $message = 'Attendance confirmation completed by both parties.';
            } else {
                $message = 'Your attendance confirmation recorded. Waiting for student confirmation.';
            }
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error("Error confirming teacher attendance: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
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
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
        $packageInfo = \DB::table('user_lesson_trackings')
            ->where('student_id', $student->id)
            ->where('teacher_id', $meeting->teacher_id)
            ->where('status', 'active')
            ->first();
        $packageData = (object)[
            'meeting_id' => $id,
            'meeting_topic' => $meeting->topic ?? $meeting->agenda ?? 'Lesson',
            'meeting_time' => \Carbon\Carbon::parse($meeting->start_time)->format('M d, Y H:i'),
        ];
        if ($packageInfo) {
            $packageData->package_summary = $packageInfo->package_summary ?? "Package with {$meeting->teacher->name}";
            $packageData->package_id = $packageInfo->id;
        }
        $meeting->teacher->notify(new \App\Notifications\LessonDeductedNotification(
            $packageData,
            $student,
            'cancel'
        ));
        return back()->with('success', 'Lesson cancelled successfully.');
    }

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
        $meeting = ZoomMeeting::with('teacher')->findOrFail($id);
        $packageInfo = \DB::table('user_lesson_trackings')
            ->where('student_id', $student->id)
            ->where('teacher_id', $meeting->teacher_id)
            ->where('status', 'active')
            ->first();
        $packageData = (object)[
            'meeting_id' => $id,
            'meeting_topic' => $meeting->topic ?? $meeting->agenda ?? 'Lesson',
            'meeting_time' => \Carbon\Carbon::parse($meeting->start_time)->format('M d, Y H:i'),
            'rescheduled_time' => \Carbon\Carbon::parse($request->rescheduled_time)->format('M d, Y H:i')
        ];
        if ($packageInfo) {
            $packageData->package_summary = $packageInfo->package_summary ?? "Package with {$meeting->teacher->name}";
            $packageData->package_id = $packageInfo->id;
        }
        $meeting->teacher->notify(new \App\Notifications\LessonDeductedNotification(
            $packageData,
            $student,
            'reschedule'
        ));
        return back()->with('success', 'Lesson rescheduled successfully.');
    }
}
