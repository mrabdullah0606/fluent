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
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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

    // Accessors
    public function getFormattedTimeRangeAttribute()
    {
        return Carbon::parse($this->start_time)->format('h:i A') . ' - ' .
            Carbon::parse($this->end_time)->format('h:i A');
    }

    public function getFormattedDateAttribute()
    {
        return $this->available_date->format('l, F j');
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

    public static function markDayUnavailable($teacherId, $date)
    {
        return self::forTeacher($teacherId)
            ->forDate($date)
            ->update(['is_available' => false, 'status' => 'unavailable']);
    }
}
