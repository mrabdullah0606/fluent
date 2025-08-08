<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeacherAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'available_date',
        'start_time',
        'end_time',
        'is_available',
        'status',
        'notes'
    ];

    protected $casts = [
        'available_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('status', 'available');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('available_date', $date);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('available_date', [$startDate, $endDate]);
    }

    // Updated Accessors with better error handling
    public function getFormattedTimeRangeAttribute()
    {
        try {
            // Handle different time formats
            if (is_string($this->start_time) && is_string($this->end_time)) {
                // If it's already in H:i format
                if (preg_match('/^\d{2}:\d{2}$/', $this->start_time)) {
                    $start = Carbon::createFromFormat('H:i', $this->start_time);
                } else {
                    $start = Carbon::parse($this->start_time);
                }

                if (preg_match('/^\d{2}:\d{2}$/', $this->end_time)) {
                    $end = Carbon::createFromFormat('H:i', $this->end_time);
                } else {
                    $end = Carbon::parse($this->end_time);
                }
            } else {
                $start = Carbon::parse($this->start_time);
                $end = Carbon::parse($this->end_time);
            }

            return $start->format('g:i A') . ' - ' . $end->format('g:i A');
        } catch (\Exception $e) {
            // Fallback to raw values if parsing fails
            return $this->start_time . ' - ' . $this->end_time;
        }
    }

    public function getFormattedDateAttribute()
    {
        try {
            return $this->available_date->format('l, F j');
        } catch (\Exception $e) {
            return $this->available_date;
        }
    }

    // Additional accessor for start time in 12-hour format
    public function getStartTimeFormattedAttribute()
    {
        try {
            if (is_string($this->start_time) && preg_match('/^\d{2}:\d{2}$/', $this->start_time)) {
                return Carbon::createFromFormat('H:i', $this->start_time)->format('g:i A');
            } else {
                return Carbon::parse($this->start_time)->format('g:i A');
            }
        } catch (\Exception $e) {
            return $this->start_time;
        }
    }

    // Additional accessor for end time in 12-hour format
    public function getEndTimeFormattedAttribute()
    {
        try {
            if (is_string($this->end_time) && preg_match('/^\d{2}:\d{2}$/', $this->end_time)) {
                return Carbon::createFromFormat('H:i', $this->end_time)->format('g:i A');
            } else {
                return Carbon::parse($this->end_time)->format('g:i A');
            }
        } catch (\Exception $e) {
            return $this->end_time;
        }
    }

    // Static methods
    public static function getAvailabilityForDate($teacherId, $date)
    {
        return self::forTeacher($teacherId)
            ->forDate($date)
            ->available()
            ->orderBy('start_time')
            ->get();
    }

    // Updated method - now marks as unavailable (kept for backward compatibility)
    public static function markDayUnavailable($teacherId, $date)
    {
        return self::forTeacher($teacherId)
            ->forDate($date)
            ->update(['is_available' => false, 'status' => 'unavailable']);
    }

    // New method - completely removes time slots for a day
    public static function deleteDaySlots($teacherId, $date)
    {
        return self::forTeacher($teacherId)
            ->forDate($date)
            ->delete();
    }

    // Method to get count of slots for a day before deleting
    public static function getDaySlotCount($teacherId, $date)
    {
        return self::forTeacher($teacherId)
            ->forDate($date)
            ->count();
    }

    // Helper method to check for overlapping slots
    public static function hasOverlappingSlots($teacherId, $date, $startTime, $endTime, $excludeId = null)
    {
        $query = self::forTeacher($teacherId)
            ->forDate($date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($query) use ($startTime, $endTime) {
                    // New slot starts during existing slot
                    $query->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })->orWhere(function ($query) use ($startTime, $endTime) {
                    // New slot ends during existing slot
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>=', $endTime);
                })->orWhere(function ($query) use ($startTime, $endTime) {
                    // New slot completely contains existing slot
                    $query->where('start_time', '>=', $startTime)
                        ->where('end_time', '<=', $endTime);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Helper method to format time consistently
    public static function formatTimeForStorage($time)
    {
        try {
            if (is_string($time) && preg_match('/^\d{2}:\d{2}$/', $time)) {
                return $time . ':00'; // Add seconds
            }
            return Carbon::parse($time)->format('H:i:s');
        } catch (\Exception $e) {
            return $time;
        }
    }
}
