<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherAvailability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();

        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->addMonth()->endOfMonth();

        $availabilities = TeacherAvailability::forTeacher($teacherId)
            ->forDateRange($currentMonth, $nextMonth)
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('available_date');

        return view('teacher.content.calendar.index', compact('availabilities'));
    }

    public function getAvailabilityForDate(Request $request)
    {
        $teacherId = Auth::id();
        $date = $request->input('date');

        Log::info('Getting availability for date:', ['date' => $date, 'teacher_id' => $teacherId]);

        $slots = TeacherAvailability::getAvailabilityForDate($teacherId, $date);

        return response()->json([
            'success' => true,
            'date' => $date,
            'slots' => $slots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'formatted_range' => $slot->formatted_time_range,
                    'status' => $slot->status,
                    'is_available' => $slot->is_available
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Time slot creation request received:', [
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);

        // Simplified validation - accept 24-hour format from HTML5 time input
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        Log::info('Validation passed:', $validated);

        $teacherId = Auth::id();
        $date = $validated['date'];
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];

        try {
            DB::beginTransaction();

            // Parse start and end times using Carbon
            $start = Carbon::createFromFormat('H:i', $startTime);
            $end = Carbon::createFromFormat('H:i', $endTime);

            Log::info('Parsed times:', [
                'start' => $start->format('H:i:s'),
                'end' => $end->format('H:i:s'),
                'original_start' => $startTime,
                'original_end' => $endTime
            ]);

            // Validate that end time is after start time
            if ($end->lte($start)) {
                return response()->json([
                    'success' => false,
                    'message' => 'End time must be after start time'
                ], 422);
            }

            // Check for overlapping slots
            $overlappingSlots = TeacherAvailability::forTeacher($teacherId)
                ->forDate($date)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        // New slot starts during existing slot
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        // New slot ends during existing slot
                        $q->where('start_time', '<', $endTime)
                            ->where('end_time', '>=', $endTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        // New slot completely contains existing slot
                        $q->where('start_time', '>=', $startTime)
                            ->where('end_time', '<=', $endTime);
                    });
                })
                ->count();

            if ($overlappingSlots > 0) {
                Log::warning('Overlapping slots detected', [
                    'teacher_id' => $teacherId,
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'overlapping_count' => $overlappingSlots
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Time slots overlap with existing availability'
                ], 422);
            }

            // Generate hourly slots
            $slots = [];
            $current = $start->copy();
            $slotCount = 0;

            while ($current->lt($end)) {
                $slotEnd = $current->copy()->addHour();

                // Don't exceed the end time
                if ($slotEnd->gt($end)) {
                    $slotEnd = $end->copy();
                }

                // Only create slot if it has meaningful duration (at least 1 minute)
                if ($slotEnd->gt($current)) {
                    $slots[] = [
                        'teacher_id' => $teacherId,
                        'available_date' => $date,
                        'start_time' => $current->format('H:i:s'),
                        'end_time' => $slotEnd->format('H:i:s'),
                        'is_available' => true,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    Log::info('Created slot:', [
                        'start' => $current->format('H:i:s'),
                        'end' => $slotEnd->format('H:i:s')
                    ]);

                    $slotCount++;
                }

                $current->addHour();

                // Safety break to prevent infinite loops
                if ($slotCount > 24) {
                    break;
                }
            }

            if (empty($slots)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid time slots could be created from the given time range'
                ], 422);
            }

            // Insert slots
            $inserted = TeacherAvailability::insert($slots);

            if (!$inserted) {
                throw new \Exception('Failed to insert time slots into database');
            }

            Log::info('Inserted slots successfully:', ['count' => count($slots)]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Time slots added successfully',
                'slots_added' => count($slots),
                'data' => $slots
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error in store method:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in time slot creation:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding time slots: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $teacherId = Auth::id();

        $slot = TeacherAvailability::where('id', $id)
            ->where('teacher_id', $teacherId)
            ->first();

        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Time slot not found'
            ], 404);
        }

        Log::info('Deleting time slot:', ['slot_id' => $id, 'teacher_id' => $teacherId]);

        $slot->delete();

        return response()->json([
            'success' => true,
            'message' => 'Time slot removed successfully'
        ]);
    }

    public function markDayUnavailable(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $teacherId = Auth::id();
        $date = $request->input('date');

        Log::info('Marking day unavailable:', ['date' => $date, 'teacher_id' => $teacherId]);

        try {
            DB::beginTransaction();

            // Get all slots for the day before deleting
            $slotsToDelete = TeacherAvailability::forTeacher($teacherId)
                ->forDate($date)
                ->get();

            $deletedCount = $slotsToDelete->count();

            Log::info('Found slots to delete:', [
                'count' => $deletedCount,
                'slots' => $slotsToDelete->pluck('id')->toArray()
            ]);

            // Delete all time slots for the day
            $actualDeletedCount = TeacherAvailability::forTeacher($teacherId)
                ->forDate($date)
                ->delete();

            Log::info('Deleted slots:', ['actual_deleted' => $actualDeletedCount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Day marked as unavailable and all time slots removed',
                'deleted_slots' => $actualDeletedCount
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error marking day unavailable:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'date' => $date,
                'teacher_id' => $teacherId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error marking day unavailable: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMonthlyAvailability(Request $request)
    {
        $teacherId = Auth::id();
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        Log::info('Getting monthly availability:', [
            'teacher_id' => $teacherId,
            'year' => $year,
            'month' => $month
        ]);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $availabilities = TeacherAvailability::forTeacher($teacherId)
            ->forDateRange($startDate, $endDate)
            ->available()
            ->get()
            ->groupBy(function ($item) {
                return $item->available_date->format('Y-m-d');
            });

        return response()->json([
            'success' => true,
            'month' => $month,
            'year' => $year,
            'availabilities' => $availabilities
        ]);
    }
}
