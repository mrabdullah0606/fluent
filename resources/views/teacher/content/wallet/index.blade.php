@extends('teacher.master.master')

@section('title', 'Wallet - FluentAll')

@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <h1 class="text-3xl font-bold text-foreground mb-8 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-3 h-8 w-8 text-primary">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
                    </svg> {{ __('welcome.key_365') }}
                </h1>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Balance Card --}}
                <div class="mb-8">
                    <div class="rounded-lg border bg-gradient-to-r from-primary to-yellow-500 text-white shadow-lg">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="tracking-tight text-lg font-medium">{{ __('welcome.key_366') }}</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <p class="text-5xl font-bold">${{ number_format($wallet->balance, 2) }}</p>
                            <p class="opacity-80 mt-1">{{ __('welcome.key_367') }}</p>
                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm opacity-90">
                                <div>
                                    <p class="text-xs uppercase tracking-wider">{{ __('welcome.key_368') }}</p>
                                    <p class="text-xl font-semibold">${{ number_format($wallet->total_earned, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wider">{{ __('welcome.key_369') }}</p>
                                    <p class="text-xl font-semibold">${{ number_format($wallet->total_withdrawn, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div>
                    <div class="grid grid-cols-3 gap-2 bg-muted rounded-md text-muted-foreground mb-4">
                        <button onclick="showTab('withdraw-tab')" class="tab-btn active-tab">{{ __('welcome.key_370') }}</button>
                        <button onclick="showTab('history-tab')" class="tab-btn">{{ __('welcome.key_371') }}</button>
                        <button onclick="showTab('settings-tab')" class="tab-btn">{{ __('welcome.key_372') }}</button>
                    </div>

                    {{-- Withdraw Tab --}}
                    <div id="withdraw-tab" class="tab-content">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-2">{{ __('welcome.key_373') }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">
                                {{ __('welcome.key_374') }}
                            </p>
                            
                            @if(!$paymentSettings || !$paymentSettings->is_verified)
                                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                                    <strong>{{ __('welcome.key_376') }}</strong> {{ __('welcome.key_377') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <button 
                                    class="btn-primary {{ ($paymentSettings && $paymentSettings->paypal_email) ? '' : 'opacity-50 cursor-not-allowed' }}" 
                                    onclick="{{ ($paymentSettings && $paymentSettings->paypal_email) ? 'openModal(\'paypal\')' : '' }}"
                                    {{ ($paymentSettings && $paymentSettings->paypal_email) ? '' : 'disabled' }}>
                                    Withdraw via PayPal
                                   @if($paymentSettings && $paymentSettings->paypal_email)
                                        <span class="block text-xs opacity-75">{{ $paymentSettings->paypal_email }}</span>
                                    @endif
                                </button>
                                
                                <button 
                                    class="btn-primary {{ ($paymentSettings && $paymentSettings->payoneer_id) ? '' : 'opacity-50 cursor-not-allowed' }}"
                                    onclick="{{ ($paymentSettings && $paymentSettings->payoneer_id) ? 'openModal(\'payoneer\')' : '' }}"
                                    {{ ($paymentSettings && $paymentSettings->payoneer_id) ? '' : 'disabled' }}>
                                    {{ __('welcome.key_379') }}
                                </button>
                                
                                <button 
                                    class="btn-primary {{ ($paymentSettings && $paymentSettings->wise_account) ? '' : 'opacity-50 cursor-not-allowed' }}"
                                    onclick="{{ ($paymentSettings && $paymentSettings->wise_account) ? 'openModal(\'wise\')' : '' }}"
                                    {{ ($paymentSettings && $paymentSettings->wise_account) ? '' : 'disabled' }}>
                                    {{ __('welcome.key_380') }}
                                </button>
                            </div>

                            {{-- Recent Withdrawal Requests --}}
                            @if($wallet->withdrawalRequests()->latest()->limit(3)->exists())
                                <div class="mt-8">
                                    <h4 class="text-lg font-semibold mb-3">{{ __('welcome.key_382') }}</h4>
                                    <div class="space-y-2">
                                        @foreach($wallet->withdrawalRequests()->latest()->limit(3)->get() as $withdrawal)
                                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                                <div>
                                                    <p class="font-medium">${{ number_format($withdrawal->amount, 2) }} via {{ ucfirst($withdrawal->method) }}</p>
                                                    <p class="text-sm text-gray-600">{{ $withdrawal->created_at->format('M j, Y g:i A') }}</p>
                                                </div>
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Transaction History Tab --}}
                    <div id="history-tab" class="tab-content hidden">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-4">{{ __('welcome.key_371') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto text-sm text-left border">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border">{{ __('welcome.key_384') }}</th>
                                            <th class="p-2 border">{{ __('welcome.key_385') }}</th>
                                            <th class="p-2 border">{{ __('welcome.key_386') }}</th>
                                            <th class="p-2 border">{{ __('welcome.key_387') }}</th>
                                            <th class="p-2 border">{{ __('welcome.key_388') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $txn)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border">
                                                {{ $txn->created_at->format('M j, Y') }}
                                                <br>
                                                <small class="text-gray-500">{{ $txn->created_at->format('g:i A') }}</small>
                                            </td>
                                            <td class="p-2 border">
                                                {{ $txn->description }}
                                                 @if($txn->payment_id)
                                                    <br><small class="text-blue-600">Payment #{{ $txn->payment_id }}</small>
                                                @endif
                                                @if($txn->withdrawal_id)
                                                    <br><small class="text-purple-600">Withdrawal #{{ $txn->withdrawal_id }}</small>
                                                @endif
                                            </td>
                                            <td class="p-2 border">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $txn->type == 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($txn->type) }}
                                                </span>
                                                <br>
                                                <small class="text-gray-500">{{ ucfirst($txn->category) }}</small>
                                            </td>
                                            <td class="p-2 border font-bold {{ $txn->type == 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $txn->type == 'credit' ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                                            </td>
                                            <td class="p-2 border text-gray-600">
                                                ${{ number_format($txn->balance_after, 2) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-gray-500">
                                                {{ __('welcome.key_391') }}
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                           {{-- Pagination --}}
                            @if($transactions->hasPages())
                                <div class="mt-4">
                                    {{ $transactions->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Settings Tab --}}
                    <div id="settings-tab" class="tab-content hidden">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-4">{{ __('welcome.key_372') }}</h3>
                            
                            @if(!$paymentSettings)
                                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                                    <strong>{{ __('welcome.key_393') }}</strong> {{ __('welcome.key_394') }}
                                </div>
                            @endif

                            <form action="{{ route('teacher.wallet.payment-settings') }}" method="POST" class="space-y-6">
                                @csrf
                                
                                {{-- PayPal Settings --}}
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-lg font-medium">{{ __('welcome.key_395') }}</h4>
                                        @if($paymentSettings && $paymentSettings->paypal_email && $paymentSettings->is_verified)
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ __('welcome.key_396') }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="paypal_email" class="block text-sm font-medium mb-1">{{ __('welcome.key_397') }}</label>
                                        <input 
                                            type="email" 
                                            name="paypal_email" 
                                            id="paypal_email" 
                                            value="{{ $paymentSettings->paypal_email ?? old('paypal_email') }}"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                                            placeholder="your-paypal@email.com"
                                        >
                                        @error('paypal_email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Payoneer Settings --}}
                                <div class="border rounded-lg p-4">
                                    <h4 class="text-lg font-medium mb-3">{{ __('welcome.key_398') }}</h4>
                                    <div>
                                        <label for="payoneer_id" class="block text-sm font-medium mb-1">{{ __('welcome.key_399') }}</label>
                                        <input 
                                            type="text" 
                                            name="payoneer_id" 
                                            id="payoneer_id" 
                                            value="{{ $paymentSettings->payoneer_id ?? old('payoneer_id') }}"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                                            placeholder="Enter your Payoneer ID"
                                        >
                                        @error('payoneer_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Wise Settings --}}
                                <div class="border rounded-lg p-4">
                                    <h4 class="text-lg font-medium mb-3">{{ __('welcome.key_400') }}</h4>
                                    <div>
                                        <label for="wise_account" class="block text-sm font-medium mb-1">{{ __('welcome.key_401') }}</label>
                                        <input 
                                            type="email" 
                                            name="wise_account" 
                                            id="wise_account" 
                                            value="{{ $paymentSettings->wise_account ?? old('wise_account') }}"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                                            placeholder="your-wise@email.com"
                                        >
                                        @error('wise_account')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Preferred Method --}}
                                <div class="border rounded-lg p-4">
                                    <h4 class="text-lg font-medium mb-3">{{ __('welcome.key_402') }}</h4>
                                    <select 
                                        name="preferred_method" 
                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                                    >
                                        <option value="paypal" {{ ($paymentSettings && $paymentSettings->preferred_method == 'paypal') ? 'selected' : '' }}>{{ __('welcome.key_395') }}</option>
                                        <option value="payoneer" {{ ($paymentSettings && $paymentSettings->preferred_method == 'payoneer') ? 'selected' : '' }}>{{ __('welcome.key_398') }}</option>
                                        <option value="wise" {{ ($paymentSettings && $paymentSettings->preferred_method == 'wise') ? 'selected' : '' }}>{{ __('welcome.key_403') }}</option>
                                    </select>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                                        {{ __('welcome.key_404') }}
                                    </button>
                                </div>
                            </form>

                            {{-- Current Settings Display --}}
                            @if($paymentSettings)
                                <div class="mt-8 pt-6 border-t">
                                    <h4 class="text-lg font-semibold mb-3">{{ __('welcome.key_405') }}</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ __('welcome.key_406') }}</span>
                                            <span>{{ $paymentSettings->paypal_email ?? 'Not Set' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ __('welcome.key_407') }}</span>
                                            <span>{{ $paymentSettings->payoneer_id ?? 'Not Set' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ __('welcome.key_408') }}</span>
                                            <span>{{ $paymentSettings->wise_account ?? 'Not Set' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ __('welcome.key_409') }}</span>
                                            <span>{{ ucfirst($paymentSettings->preferred_method) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ __('welcome.key_410') }}</span>
                                            <span class="{{ $paymentSettings->is_verified ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $paymentSettings->is_verified ? 'Verified' : 'Pending Verification' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawal Modal -->
        <div id="withdrawModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <h2 class="text-xl font-semibold mb-2">{{ __('welcome.key_373') }}</h2>
                <p class="text-sm text-muted-foreground mb-4">
                    {{ __('welcome.key_411') }} <span id="withdrawal-method"></span>
                    <br>{{ __('welcome.key_412') }} <strong>${{ number_format($wallet->balance, 2) }}</strong>
                </p>

                <form action="{{ route('teacher.wallet.withdraw') }}" method="POST">
                    @csrf
                    <input type="hidden" name="method" id="withdrawal-method-input">
                    
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium mb-1">{{ __('welcome.key_413') }}</label>
                        <input 
                            type="number" 
                            name="amount" 
                            id="amount" 
                            step="0.01" 
                            min="1" 
                            max="{{ $wallet->balance }}"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                            required
                        >
                        <small class="text-gray-500">{{ __('welcome.key_414') }}</small>
                    </div>

                    <div id="account-info" class="mb-4 p-3 bg-gray-50 rounded">
                        <!-- Account info will be populated by JavaScript -->
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">{{ __('welcome.key_262') }}</button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">{{ __('welcome.key_415') }}</button>
                    </div>
                </form>

                <button class="absolute top-2 right-3 text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    ✕
                </button>
            </div>
        </div>
    </main>

    {{-- JavaScript --}}
    <script>
        // Payment settings data for JavaScript
        const paymentSettings = @json($paymentSettings);
        
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active-tab'));

            document.getElementById(tabId).classList.remove('hidden');
            event.target.classList.add('active-tab');
        }

        function openModal(method) {
            const modal = document.getElementById('withdrawModal');
            const methodSpan = document.getElementById('withdrawal-method');
            const methodInput = document.getElementById('withdrawal-method-input');
            const accountInfo = document.getElementById('account-info');
            
            methodSpan.textContent = method.charAt(0).toUpperCase() + method.slice(1);
            methodInput.value = method;
            
            // Show account information
            let accountHtml = '';
            if (paymentSettings) {
                switch(method) {
                    case 'paypal':
                        accountHtml = `<strong>PayPal Account:</strong> ${paymentSettings.paypal_email || 'Not configured'}`;
                        break;
                    case 'payoneer':
                        accountHtml = `<strong>Payoneer ID:</strong> ${paymentSettings.payoneer_id || 'Not configured'}`;
                        break;
                    case 'wise':
                        accountHtml = `<strong>Wise Account:</strong> ${paymentSettings.wise_account || 'Not configured'}`;
                        break;
                }
            }
            accountInfo.innerHTML = accountHtml;
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('withdrawModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

    {{-- Styles --}}
    <style>
        .tab-btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .active-tab {
            background-color: white;
            color: black;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            @apply inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 w-full bg-primary/10 text-primary hover:bg-primary/20 border border-primary transition-colors;
        }

        .btn-primary:disabled {
            @apply cursor-not-allowed opacity-50;
        }
    </style>
@endsection