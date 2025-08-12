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

        // REQUIREMENT 1: When payment is created with successful status, add to teacher wallet
        static::created(function ($payment) {
            if ($payment->status === 'successful' && !$payment->wallet_processed) {
                $walletService = app(WalletService::class);

                // Add base_price to teacher wallet (this is teacher's earning)
                $walletService->addEarning(
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

                $walletService->addEarning(
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
}
