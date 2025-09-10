<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Exception;

class AdminWalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function withdrawals(Request $request)
{
    $query = WithdrawalRequest::with(['teacher'])
        ->orderBy('created_at', 'desc');

    // Filter by status
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $withdrawals = $query->paginate(50);

    // ✅ Also fetch latest wallet transactions
    $transactions = \App\Models\WalletTransaction::with(['teacher', 'payment', 'withdrawalRequest'])
        ->orderBy('created_at', 'desc')
        ->paginate(20, ['*'], 'transactions_page'); // keep pagination separate

    return view('admin.content.withdrawals.index', compact('withdrawals', 'transactions'));
}

    public function showWithdrawal($id)
    {
        try {
            $withdrawal = WithdrawalRequest::with(['teacher'])->findOrFail($id);
            return response()->json($withdrawal);
        } catch (Exception $e) {
            return response()->json(['error' => 'Withdrawal not found'], 404);
        }
    }

    // REQUIREMENT 3: Admin approves withdrawal - deduct amount from teacher wallet
    public function approveWithdrawal(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string',
        ]);

        try {
            $withdrawal = WithdrawalRequest::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error', 'This withdrawal has already been processed.');
            }

            // REQUIREMENT 3: This will deduct the amount from teacher's wallet
            $this->walletService->completeWithdrawal($id, $request->transaction_id);

            // Update admin notes if provided
            if ($request->admin_notes) {
                $withdrawal->refresh();
                $withdrawal->update(['admin_notes' => $request->admin_notes]);
            }

            return redirect()->back()->with('success', "Withdrawal approved! \${$withdrawal->amount} has been deducted from teacher's wallet.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }
    }

    public function rejectWithdrawal(Request $request, $id)
    {
        $request->validate([
            'failure_reason' => 'required|string|max:1000',
        ]);

        try {
            $withdrawal = WithdrawalRequest::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error', 'This withdrawal has already been processed.');
            }

            $withdrawal->update([
                'status' => 'failed',
                'processed_date' => now(),
                'failure_reason' => $request->failure_reason,
            ]);

            return redirect()->back()->with('success', 'Withdrawal rejected. Teacher wallet balance remains unchanged.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }
}
