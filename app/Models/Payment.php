<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'student_id',
        'teacher_id',
        'summary',
        'base_price',
        'fee',
        'total',
        'type',
        'payment_method',
        'status',
    ];

     // Relationships (if needed)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // public function course()
    // {
    //     return $this->belongsTo(Course::class);
    // }
}
