<?php

namespace App\Models;

use App\Services\WalletService;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
        'wallet_processed',
        'wallet_processed_at',
    ];

    protected $casts = [
        'wallet_processed' => 'boolean',
        'wallet_processed_at' => 'datetime',
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            if ($payment->status === 'successful' && !$payment->wallet_processed) {
                // Store TOTAL amount to admin wallet instead of base_price
                app(\App\Services\WalletService::class)->addFullAmountToAdminWallet(
                    (float) $payment->total, // Changed from base_price to total
                    'Full payment added to admin wallet: ' . $payment->summary,
                    $payment->id
                );

                $payment->wallet_processed = true;
                $payment->wallet_processed_at = now();
                $payment->save();
            }
        });

        static::updated(function ($payment) {
            if ($payment->status === 'successful' && !$payment->wallet_processed && $payment->isDirty('status')) {
                // Store TOTAL amount to admin wallet instead of base_price
                app(\App\Services\WalletService::class)->addFullAmountToAdminWallet(
                    (float) $payment->total, // Changed from base_price to total
                    'Full payment added to admin wallet (update): ' . $payment->summary,
                    $payment->id
                );

                $payment->wallet_processed = true;
                $payment->wallet_processed_at = now();
                $payment->save();
            }
        });
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function adminWalletTransactions()
    {
        return $this->hasMany(AdminWalletTransaction::class);
    }

    /**
     * Get teacher's earning amount (80% of base_price)
     */
    public function getTeacherEarningAttribute()
    {
        return round($this->base_price * 0.80, 2);
    }

    /**
     * Get admin's commission amount (20% of base_price)
     */
    public function getAdminCommissionAttribute()
    {
        return round($this->base_price * 0.20, 2);
    }
}
