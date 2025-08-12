<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherWallet extends Model
{
    protected $fillable = [
        'teacher_id',
        'balance',
        'total_earned',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'teacher_id', 'teacher_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'teacher_id', 'teacher_id');
    }
}
