<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'teacher_id',
        'payment_id',
        'withdrawal_id',
        'type',
        'category',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function withdrawalRequest()
    {
        return $this->belongsTo(WithdrawalRequest::class, 'withdrawal_id');
    }

    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeEarnings($query)
    {
        return $query->where('category', 'earning');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('category', 'withdrawal');
    }
}
