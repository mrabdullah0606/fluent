<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminWallet;
use App\Models\AdminWalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;

class AdminWalletDashboardController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Show admin wallet dashboard
     */
    public function index()
    {
        $walletStats = $this->walletService->getAdminWalletStats();
        return view('admin.content.wallet.index', [
            'wallet' => $walletStats['wallet'] ?? null,
            'balance' => $walletStats['balance'],
            'totalEarned' => $walletStats['total_earned'],
            'totalWithdrawn' => $walletStats['total_withdrawn'],
            'recentTransactions' => $walletStats['recent_transactions'],
        ]);
    }

    /**
     * Show all admin transactions
     */
    public function transactions(Request $request)
    {
        $adminWallet = AdminWallet::getMainAdminWallet();

        if (!$adminWallet) {
            return view('admin.content.wallet.transactions', [
                'transactions' => collect([]),
                'wallet' => null
            ]);
        }

        $query = AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
            ->with(['payment']);

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category') && in_array($request->category, ['commission', 'withdrawal', 'refund', 'adjustment'])) {
            $query->where('category', $request->category);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.content.wallet.transactions', compact('transactions', 'adminWallet'));
    }

    /**
     * Get commission analytics
     */
    public function analytics(Request $request)
    {
        $adminWallet = AdminWallet::getMainAdminWallet();

        if (!$adminWallet) {
            return view('admin.content.wallet.analytics', [
                'dailyEarnings' => [],
                'monthlyEarnings' => [],
                'topTeachers' => [],
                'totalStats' => [
                    'today' => 0,
                    'this_week' => 0,
                    'this_month' => 0,
                    'total' => 0
                ]
            ]);
        }

        // Daily earnings for last 30 days
        $dailyEarnings = AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
            ->where('type', 'credit')
            ->where('category', 'commission')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly earnings for last 12 months
        $monthlyEarnings = AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
            ->where('type', 'credit')
            ->where('category', 'commission')
            ->whereDate('created_at', '>=', now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top earning teachers (by commission generated)
        $topTeachers = AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
            ->where('type', 'credit')
            ->where('category', 'commission')
            ->with('payment.teacher')
            ->selectRaw('payment_id, SUM(amount) as total_commission')
            ->groupBy('payment_id')
            ->orderBy('total_commission', 'desc')
            ->limit(10)
            ->get();

        // Total stats
        $totalStats = [
            'today' => AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
                ->where('type', 'credit')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_week' => AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
                ->where('type', 'credit')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount'),
            'this_month' => AdminWalletTransaction::where('admin_id', $adminWallet->admin_id)
                ->where('type', 'credit')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total' => $adminWallet->total_earned
        ];

        return view('admin.content.wallet.analytics', compact(
            'dailyEarnings',
            'monthlyEarnings',
            'topTeachers',
            'totalStats',
            'adminWallet'
        ));
    }
}
