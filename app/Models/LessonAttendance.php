<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_lesson_tracking_id',
        'student_id',
        'teacher_id',
        'lesson_date',
        'attendance_status',
        'notes',
    ];

    protected $casts = [
        'lesson_date' => 'date',
    ];

    public function userLessonTracking()
    {
        return $this->belongsTo(UserLessonTracking::class, 'user_lesson_tracking_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
