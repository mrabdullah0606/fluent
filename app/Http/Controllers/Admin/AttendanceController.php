<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonAttendance;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Show pending attendance approvals
     */
    public function index(Request $request)
    {
        $query = LessonAttendance::with(['student', 'teacher', 'meeting', 'payment'])
            ->where('status', 'confirmed')
            ->where('admin_status', 'pending');

        // Filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pendingAttendances = $query->latest()->paginate(20);

        // Get teachers for filter dropdown
        $teachers = \App\Models\User::where('role', 'teacher')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.content.attendances.index', compact('pendingAttendances', 'teachers'));
    }

    /**
     * Show attendance details for approval
     */
    public function show($id)
    {
        $attendance = LessonAttendance::with(['student', 'teacher', 'meeting', 'payment'])
            ->findOrFail($id);

        return view('admin.content.attendances.show', compact('attendance'));
    }

    /**
     * Approve attendance and process payment
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $attendance = LessonAttendance::where('id', $id)
                ->where('admin_status', 'pending')
                ->lockForUpdate()
                ->firstOrFail();

            // Process teacher payment only if both attended
            if ($attendance->student_attended && $attendance->teacher_attended && !$attendance->payment_released) {
                $teacherAmount = $attendance->teacher_earning;

                // Add earning to teacher wallet
                $this->walletService->addEarning(
                    $attendance->teacher_id,
                    $teacherAmount,
                    "Admin approved lesson payment for meeting #{$attendance->meeting_id}",
                    $attendance->payment_id
                );

                // Deduct from admin wallet
                $this->walletService->deductFromAdminWallet(
                    $teacherAmount,
                    "Admin approved teacher payment for lesson - Meeting #{$attendance->meeting_id}",
                    $attendance->payment_id,
                    $attendance->teacher_id
                );

                $attendance->update([
                    'payment_released' => true,
                    'payment_released_at' => now(),
                ]);
            }

            // Update admin approval status
            $attendance->update([
                'admin_status' => 'approved',
                'approved_by' => auth()->id(),
                'admin_approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            return redirect()->route('admin.attendances.index')
                ->with('success', 'Attendance approved and payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error approving attendance: " . $e->getMessage());
            return back()->with('error', 'Failed to approve attendance. Please try again.');
        }
    }

    /**
     * Reject attendance
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        try {
            $attendance = LessonAttendance::where('id', $id)
                ->where('admin_status', 'pending')
                ->firstOrFail();

            $attendance->update([
                'admin_status' => 'rejected',
                'approved_by' => auth()->id(),
                'admin_approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            return redirect()->route('admin.attendances.index')
                ->with('success', 'Attendance rejected successfully.');
        } catch (\Exception $e) {
            \Log::error("Error rejecting attendance: " . $e->getMessage());
            return back()->with('error', 'Failed to reject attendance. Please try again.');
        }
    }

    /**
     * Bulk approve attendances
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'exists:lesson_attendances,id'
        ]);

        try {
            DB::beginTransaction();

            $processedCount = 0;

            foreach ($request->attendance_ids as $attendanceId) {
                $attendance = LessonAttendance::where('id', $attendanceId)
                    ->where('admin_status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if ($attendance && $attendance->student_attended && $attendance->teacher_attended && !$attendance->payment_released) {
                    $teacherAmount = $attendance->teacher_earning;

                    $this->walletService->addEarning(
                        $attendance->teacher_id,
                        $teacherAmount,
                        "Bulk approved lesson payment for meeting #{$attendance->meeting_id}",
                        $attendance->payment_id
                    );

                    $this->walletService->deductFromAdminWallet(
                        $teacherAmount,
                        "Bulk approved teacher payment for lesson - Meeting #{$attendance->meeting_id}",
                        $attendance->payment_id,
                        $attendance->teacher_id
                    );

                    $attendance->update([
                        'admin_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'admin_approved_at' => now(),
                        'payment_released' => true,
                        'payment_released_at' => now(),
                    ]);

                    $processedCount++;
                }
            }

            DB::commit();

            return redirect()->route('admin.attendances.index')
                ->with('success', "Successfully processed {$processedCount} attendance approvals.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error in bulk approval: " . $e->getMessage());
            return back()->with('error', 'Failed to process bulk approvals. Please try again.');
        }
    }
}
