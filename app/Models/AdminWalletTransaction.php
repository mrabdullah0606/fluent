<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWalletTransaction extends Model
{
    protected $fillable = [
        'admin_id',
        'payment_id',
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

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeCommissions($query)
    {
        return $query->where('category', 'commission');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('category', 'withdrawal');
    }
}
