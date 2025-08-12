<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'teacher_id',
        'amount',
        'method',
        'account_details',
        'status',
        'request_date',
        'processed_date',
        'transaction_id',
        'admin_notes',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'account_details' => 'array',
        'request_date' => 'datetime',
        'processed_date' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'withdrawal_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
