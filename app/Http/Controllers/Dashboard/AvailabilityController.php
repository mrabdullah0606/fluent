<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherAvailability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        dd(1);
        return view('teacher.content.calendar.index', compact('availabilities'));
    }

    public function getAvailabilityForDate(Request $request)
    {
        $teacherId = Auth::id();
        $date = $request->input('date');

        $slots = TeacherAvailability::getAvailabilityForDate($teacherId, $date);

        return response()->json([
            'success' => true,
            'date' => $date,
            'slots' => $slots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => Carbon::parse($slot->start_time)->format('h:i A'),
                    'end_time' => Carbon::parse($slot->end_time)->format('h:i A'),
                    'formatted_range' => $slot->formatted_time_range,
                    'status' => $slot->status,
                    'is_available' => $slot->is_available
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'start_ampm' => 'required|in:am,pm',
            'end_ampm' => 'required|in:am,pm'
        ]);

        $teacherId = Auth::id();
        $date = $request->input('date');

        // Convert 12-hour format to 24-hour format
        $startTime = $this->convertTo24Hour($request->start_time, $request->start_ampm);
        $endTime = $this->convertTo24Hour($request->end_time, $request->end_ampm);

        try {
            DB::beginTransaction();

            // Generate hourly slots
            $slots = [];
            $current = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);

            while ($current->lt($end)) {
                $slotEnd = $current->copy()->addHour();

                // Check for existing slot
                $existingSlot = TeacherAvailability::forTeacher($teacherId)
                    ->forDate($date)
                    ->where('start_time', $current->format('H:i:s'))
                    ->where('end_time', $slotEnd->format('H:i:s'))
                    ->first();

                if (!$existingSlot) {
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
                }

                $current->addHour();
            }

            if (!empty($slots)) {
                TeacherAvailability::insert($slots);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Time slots added successfully',
                'slots_added' => count($slots)
            ]);
        } catch (\Exception $e) {
            DB::rollback();

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

        $updatedCount = TeacherAvailability::markDayUnavailable($teacherId, $date);

        return response()->json([
            'success' => true,
            'message' => 'Day marked as unavailable',
            'updated_slots' => $updatedCount
        ]);
    }

    public function getMonthlyAvailability(Request $request)
    {
        $teacherId = Auth::id();
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

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

    private function convertTo24Hour($time, $ampm)
    {
        $carbon = Carbon::createFromFormat('H:i', $time);

        if ($ampm === 'pm' && $carbon->hour !== 12) {
            $carbon->addHours(12);
        } elseif ($ampm === 'am' && $carbon->hour === 12) {
            $carbon->subHours(12);
        }

        return $carbon->format('H:i');
    }
}
