<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPaymentSetting extends Model
{
    protected $fillable = [
        'teacher_id',
        'paypal_email',
        'payoneer_id',
        'wise_account',
        'bank_account_number',
        'bank_name',
        'bank_routing_number',
        'preferred_method',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
