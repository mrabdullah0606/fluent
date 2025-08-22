<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserLessonTracking extends Model
{
    use HasFactory;

    protected $table = 'user_lesson_trackings';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'payment_id',
        'payment_type',
        'package_summary',
        'total_lessons_purchased',
        'lessons_taken',
        'lessons_remaining',
        'price_per_lesson',
        'status',
        'purchase_date',
        'expiry_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'price_per_lesson' => 'decimal:2'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(LessonAttendance::class, 'user_lesson_tracking_id');
    }

    public function completedLessons()
    {
        return $this->attendanceRecords()->where('attendance_status', 'attended');
    }

    // Helper Methods
    public function getProgressPercentage()
    {
        if ($this->total_lessons_purchased == 0) return 0;
        return round(($this->lessons_taken / $this->total_lessons_purchased) * 100, 1);
    }

    public function canTakeLesson()
    {
        return $this->status === 'active' && $this->lessons_remaining > 0;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function updateLessonsCount()
    {
        $attendedLessons = $this->attendanceRecords()->where('attendance_status', 'attended')->count();
        $this->update([
            'lessons_taken' => $attendedLessons,
            'lessons_remaining' => $this->total_lessons_purchased - $attendedLessons,
            'status' => $attendedLessons >= $this->total_lessons_purchased ? 'completed' : 'active'
        ]);
    }

    // Auto-create tracking when payment is made
    public static function createFromPayment($payment)
    {
        // Extract lesson count from summary (e.g., "10-Lesson Package" -> 10)
        preg_match('/(\d+)-Lesson/', $payment->summary, $matches);
        $lessonCount = isset($matches[1]) ? (int)$matches[1] : 1;

        return self::create([
            'student_id' => $payment->student_id,
            'teacher_id' => $payment->teacher_id,
            'payment_id' => $payment->id,
            'payment_type' => $payment->type,
            'package_summary' => $payment->summary,
            'total_lessons_purchased' => $lessonCount,
            'lessons_taken' => 0,
            'lessons_remaining' => $lessonCount,
            'price_per_lesson' => $lessonCount > 0 ? $payment->base_price / $lessonCount : $payment->base_price,
            'status' => 'active',
            'purchase_date' => now()->toDateString(),
            'expiry_date' => now()->addMonths(12)->toDateString(), // 1 year expiry
        ]);
    }
}