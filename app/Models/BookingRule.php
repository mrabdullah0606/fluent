<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'min_booking_notice',
        'booking_window',
        'break_after_lesson',
        'accepting_new_students',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
