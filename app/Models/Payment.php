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

        // UPDATED: When payment is created with successful status, distribute 80% to teacher and 20% to admin
        static::created(function ($payment) {
            if ($payment->status === 'successful' && !$payment->wallet_processed) {
                $walletService = app(WalletService::class);

                // Use base_price for distribution (80% teacher, 20% admin)
                $walletService->processPaymentDistribution(
                    $payment->teacher_id,
                    $payment->base_price,
                    "Earning from: {$payment->summary}",
                    $payment->id
                );
            }
        });

        // Also handle if payment status changes to successful
        static::updated(function ($payment) {
            if ($payment->status === 'successful' && !$payment->wallet_processed && $payment->isDirty('status')) {
                $walletService = app(WalletService::class);

                $walletService->processPaymentDistribution(
                    $payment->teacher_id,
                    $payment->base_price,
                    "Earning from: {$payment->summary}",
                    $payment->id
                );
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
