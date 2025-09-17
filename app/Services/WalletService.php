<?php

namespace App\Services;

use App\Models\AdminWallet;
use App\Models\AdminWalletTransaction;
use App\Models\Payment;
use App\Models\TeacherPaymentSetting;
use App\Models\TeacherWallet;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    // Commission rates
    const TEACHER_COMMISSION_RATE = 0.80; // 80%
    const ADMIN_COMMISSION_RATE = 0.20;   // 20%

    /**
     * Process payment and distribute between teacher (80%) and admin (20%)
     */
    public function processPaymentDistribution($teacherId, $totalAmount, $description, $paymentId = null)
    {
        return DB::transaction(function () use ($teacherId, $totalAmount, $description, $paymentId) {
            // Calculate amounts
            $teacherAmount = round($totalAmount * self::TEACHER_COMMISSION_RATE, 2);
            $adminAmount = round($totalAmount * self::ADMIN_COMMISSION_RATE, 2);

            // Add earning to teacher wallet (80%)
            $this->addEarning($teacherId, $teacherAmount, $description, $paymentId);

            // Add commission to admin wallet (20%)
            $this->addAdminCommission($adminAmount, $description, $paymentId, $teacherId);

            Log::info("Payment distributed", [
                'payment_id' => $paymentId,
                'teacher_id' => $teacherId,
                'total_amount' => $totalAmount,
                'teacher_amount' => $teacherAmount,
                'admin_amount' => $adminAmount,
            ]);

            return [
                'teacher_amount' => $teacherAmount,
                'admin_amount' => $adminAmount,
                'total_distributed' => $teacherAmount + $adminAmount
            ];
        });
    }

    public function addEarning($teacherId, $amount, $description, $paymentId = null)
    {
        DB::transaction(function () use ($teacherId, $amount, $description, $paymentId) {
            $wallet = TeacherWallet::firstOrCreate(
                ['teacher_id' => $teacherId],
                [
                    'balance' => 0.00,
                    'total_earned' => 0.00,
                    'total_withdrawn' => 0.00,
                ]
            );

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            $wallet->update([
                'balance' => $balanceAfter,
                'total_earned' => $wallet->total_earned + $amount,
            ]);

            WalletTransaction::create([
                'teacher_id' => $teacherId,
                'payment_id' => $paymentId,
                'type' => 'credit',
                'category' => 'earning',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
            ]);

            if ($paymentId) {
                Payment::where('id', $paymentId)->update([
                    'wallet_processed' => true,
                    'wallet_processed_at' => now(),
                ]);
            }

            Log::info("Earning added to teacher wallet", [
                'teacher_id' => $teacherId,
                'amount' => $amount,
                'new_balance' => $balanceAfter
            ]);
        });
    }

    /**
     * Add commission to admin wallet
     */
    public function addAdminCommission($amount, $description, $paymentId = null, $teacherId = null)
    {
        return DB::transaction(function () use ($amount, $description, $paymentId, $teacherId) {
            // Get main admin wallet
            $adminWallet = AdminWallet::getMainAdminWallet();

            if (!$adminWallet) {
                Log::error("Admin wallet not found for commission");
                throw new Exception("Admin wallet not found");
            }

            $balanceBefore = $adminWallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            $adminWallet->update([
                'balance' => $balanceAfter,
                'total_earned' => $adminWallet->total_earned + $amount,
            ]);

            // Create transaction record
            AdminWalletTransaction::create([
                'admin_id' => $adminWallet->admin_id,
                'payment_id' => $paymentId,
                'type' => 'credit',
                'category' => 'commission',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description . ($teacherId ? " (Teacher ID: {$teacherId})" : ""),
                'reference_id' => $paymentId ? "PAY-{$paymentId}" : null,
            ]);

            Log::info("Commission added to admin wallet", [
                'admin_id' => $adminWallet->admin_id,
                'amount' => $amount,
                'new_balance' => $balanceAfter,
                'teacher_id' => $teacherId,
                'payment_id' => $paymentId
            ]);

            return $adminWallet;
        });
    }

    public function processWithdrawal($teacherId, $amount, $method)
    {
        return DB::transaction(function () use ($teacherId, $amount, $method) {
            $wallet = TeacherWallet::where('teacher_id', $teacherId)->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new Exception('Insufficient balance');
            }

            $paymentSettings = TeacherPaymentSetting::where('teacher_id', $teacherId)->first();
            if (!$paymentSettings) {
                throw new Exception('Payment settings not configured');
            }
            $accountDetails = $this->getAccountDetails($paymentSettings, $method);
            $withdrawalRequest = WithdrawalRequest::create([
                'teacher_id' => $teacherId,
                'amount' => $amount,
                'method' => $method,
                'account_details' => $accountDetails,
                'status' => 'pending',
                'request_date' => now(),
            ]);

            return $withdrawalRequest;
        });
    }

    public function completeWithdrawal($withdrawalId, $transactionId = null)
    {
        return DB::transaction(function () use ($withdrawalId, $transactionId) {
            $withdrawal = WithdrawalRequest::findOrFail($withdrawalId);

            if ($withdrawal->status !== 'pending') {
                throw new Exception('Withdrawal is not pending');
            }

            $wallet = TeacherWallet::where('teacher_id', $withdrawal->teacher_id)->first();

            if (!$wallet || $wallet->balance < $withdrawal->amount) {
                throw new Exception('Insufficient wallet balance');
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $withdrawal->amount;
            $wallet->update([
                'balance' => $balanceAfter,
                'total_withdrawn' => $wallet->total_withdrawn + $withdrawal->amount,
            ]);
            $withdrawal->update([
                'status' => 'completed',
                'processed_date' => now(),
                'transaction_id' => $transactionId,
            ]);

            WalletTransaction::create([
                'teacher_id' => $withdrawal->teacher_id,
                'withdrawal_id' => $withdrawal->id,
                'type' => 'debit',
                'category' => 'withdrawal',
                'amount' => $withdrawal->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Withdrawal via {$withdrawal->method} - completed",
                'reference_id' => $transactionId,
            ]);

            Log::info("Withdrawal completed and amount deducted", [
                'withdrawal_id' => $withdrawalId,
                'teacher_id' => $withdrawal->teacher_id,
                'amount' => $withdrawal->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter
            ]);

            return $withdrawal;
        });
    }

    /**
     * Get admin wallet statistics
     */
    public function getAdminWalletStats()
    {
        $adminWallet = AdminWallet::getMainAdminWallet();

        if (!$adminWallet) {
            return [
                'balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
                'recent_transactions' => collect([])
            ];
        }

        $recentTransactions = AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
            ->with('payment')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'balance' => $adminWallet->balance,
            'total_earned' => $adminWallet->total_earned,
            'total_withdrawn' => $adminWallet->total_withdrawn,
            'recent_transactions' => $recentTransactions,
            'wallet' => $adminWallet
        ];
    }

    private function getAccountDetails($paymentSettings, $method)
    {
        switch ($method) {
            case 'paypal':
                return ['email' => $paymentSettings->paypal_email];
            case 'payoneer':
                return ['payoneer_id' => $paymentSettings->payoneer_id];
            case 'wise':
                return ['account' => $paymentSettings->wise_account];
            case 'bank':
                return [
                    'account_number' => $paymentSettings->bank_account_number,
                    'bank_name' => $paymentSettings->bank_name,
                    'routing_number' => $paymentSettings->bank_routing_number,
                ];
            default:
                throw new Exception('Invalid payment method');
        }
    }
}
