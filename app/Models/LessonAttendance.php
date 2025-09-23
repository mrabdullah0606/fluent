<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonAttendance extends Model
{
    protected $table = 'lesson_attendances';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'meeting_id',
        'payment_id',
        'base_price',
        'student_attended',
        'teacher_attended',
        'student_confirmed_at',
        'teacher_confirmed_at',
        'payment_released',
        'payment_released_at',
        'status'
    ];

    protected $casts = [
        'student_attended' => 'boolean',
        'teacher_attended' => 'boolean',
        'payment_released' => 'boolean',
        'student_confirmed_at' => 'datetime',
        'teacher_confirmed_at' => 'datetime',
        'payment_released_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function meeting()
    {
        return $this->belongsTo(ZoomMeeting::class, 'meeting_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Check if both parties confirmed attendance
     */
    public function getBothConfirmedAttribute()
    {
        return $this->student_attended && $this->teacher_attended;
    }

    /**
     * Get teacher earning amount (80% of base_price)
     */
    public function getTeacherEarningAttribute()
    {
        return round($this->base_price * 0.80, 2);
    }

    /**
     * Get admin commission amount (20% of base_price) 
     */
    public function getAdminCommissionAttribute()
    {
        return round($this->base_price * 0.20, 2);
    }
}
