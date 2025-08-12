<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TeacherWallet;
use App\Models\WalletTransaction;
use App\Models\TeacherPaymentSetting;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    // REQUIREMENT 2: Show teacher wallet with balance and allow withdrawal request
    public function index()
    {
        $teacher = Auth::user();

        // Get or create wallet
        $wallet = TeacherWallet::firstOrCreate(
            ['teacher_id' => $teacher->id],
            [
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]
        );

        // Get recent transactions
        $transactions = WalletTransaction::where('teacher_id', $teacher->id)
            ->with(['payment', 'withdrawalRequest'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get payment settings
        $paymentSettings = TeacherPaymentSetting::where('teacher_id', $teacher->id)->first();

        return view('teacher.content.wallet.index', compact('wallet', 'transactions', 'paymentSettings'));
    }

    // REQUIREMENT 2: Teacher makes withdrawal request
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . (Auth::user()->teacherWallet->balance ?? 0)
            ],
            'method' => 'required|in:paypal,payoneer,wise,bank',
        ]);

        try {
            $teacher = Auth::user();
            $wallet = TeacherWallet::where('teacher_id', $teacher->id)->first();

            // Check if user has sufficient balance
            if (!$wallet || $wallet->balance < $request->amount) {
                return redirect()->back()->with('error', 'Insufficient balance for withdrawal.');
            }

            // Check if payment settings are configured for the selected method
            $paymentSettings = TeacherPaymentSetting::where('teacher_id', $teacher->id)->first();
            if (!$paymentSettings) {
                return redirect()->back()->with('error', 'Please configure your payment settings first.');
            }

            // Validate method-specific settings
            $methodField = $this->getMethodField($request->method);
            if (!$paymentSettings->$methodField) {
                return redirect()->back()->with('error', 'Please configure your ' . ucfirst($request->method) . ' account first.');
            }

            // Create withdrawal request (amount not deducted yet)
            $withdrawal = $this->walletService->processWithdrawal(
                $teacher->id,
                $request->amount,
                $request->method
            );

            return redirect()->back()->with('success', 'Withdrawal request submitted successfully! We will process it within 2-3 business days.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'paypal_email' => 'nullable|email|max:255',
            'payoneer_id' => 'nullable|string|max:255',
            'wise_account' => 'nullable|email|max:255',
            'preferred_method' => 'required|in:paypal,payoneer,wise,bank',
        ]);

        try {
            $teacher = Auth::user();

            TeacherPaymentSetting::updateOrCreate(
                ['teacher_id' => $teacher->id],
                [
                    'paypal_email' => $request->paypal_email,
                    'payoneer_id' => $request->payoneer_id,
                    'wise_account' => $request->wise_account,
                    'preferred_method' => $request->preferred_method,
                    'is_verified' => false,
                ]
            );

            return redirect()->back()->with('success', 'Payment settings updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment settings: ' . $e->getMessage());
        }
    }

    public function transactions(Request $request)
    {
        $teacher = Auth::user();

        $query = WalletTransaction::where('teacher_id', $teacher->id)
            ->with(['payment', 'withdrawalRequest']);

        if ($request->has('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        if ($request->has('category') && in_array($request->category, ['earning', 'withdrawal', 'refund', 'adjustment'])) {
            $query->where('category', $request->category);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);
        $wallet = TeacherWallet::where('teacher_id', $teacher->id)->first();

        return view('teacher.content.wallet.transactions', compact('transactions', 'wallet'));
    }

    private function getMethodField($method)
    {
        switch ($method) {
            case 'paypal':
                return 'paypal_email';
            case 'payoneer':
                return 'payoneer_id';
            case 'wise':
                return 'wise_account';
            default:
                return 'paypal_email';
        }
    }
}
