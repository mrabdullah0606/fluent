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
                    </svg> My Wallet
                </h1>

                {{-- Balance Card --}}
                <div class="mb-8">
                    <div class="rounded-lg border bg-gradient-to-r from-primary to-yellow-500 text-white shadow-lg">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="tracking-tight text-lg font-medium">Available Balance</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <p class="text-5xl font-bold">${{-- {{ number_format($balance, 2) }} --}}</p>
                            <p class="opacity-80 mt-1">Ready for withdrawal</p>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div>
                    <div class="grid grid-cols-3 gap-2 bg-muted rounded-md text-muted-foreground mb-4">
                        <button onclick="showTab('withdraw-tab')" class="tab-btn active-tab">Withdraw</button>
                        <button onclick="showTab('history-tab')" class="tab-btn">Transaction History</button>
                        <button onclick="showTab('settings-tab')" class="tab-btn">Payment Settings</button>
                    </div>

                    {{-- Withdraw Tab --}}
                    <div id="withdraw-tab" class="tab-content">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-2">Withdraw Funds</h3>
                            <p class="text-sm text-muted-foreground mb-4">
                                Choose your preferred method to withdraw your earnings.
                                You can only withdraw using your connected payment method.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <button class="btn-primary" onclick="openModal()">Withdraw via PayPal</button>
                                <button class="btn-primary" disabled>Withdraw via Payoneer</button>
                                <button class="btn-primary" disabled>Withdraw via Wise</button>
                            </div>
                        </div>
                    </div>

                    {{-- Transaction History Tab --}}
                    <div id="history-tab" class="tab-content hidden">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-4">Transaction History</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto text-sm text-left border">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border">Date</th>
                                            <th class="p-2 border">Description</th>
                                            <th class="p-2 border">Type</th>
                                            <th class="p-2 border">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($transactions as $txn) --}}
                                        <tr>
                                            <td class="p-2 border">
                                                {{-- {{ \Carbon\Carbon::parse($txn->date)->format('F jS, Y') }} --}}
                                                12-4-12
                                            </td>
                                            <td class="p-2 border">{{-- {{ $txn->description }} --}}lesson amount</td>
                                            <td class="p-2 border">
                                                <span {{-- class="{{ $txn->type == 'Credit' ? 'text-green-600' : 'text-red-600' }}" --}}>
                                                    {{-- {{ $txn->type }} --}}debit
                                                </span>
                                            </td>
                                            <td {{-- class="p-2 border font-bold {{ $txn->type == 'Credit' ? 'text-green-600' : 'text-red-600' }}" --}}>
                                                {{-- ${{ number_format($txn->amount, 2) }} --}}100
                                            </td>
                                        </tr>
                                        {{-- @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Settings Tab --}}
                    <div id="settings-tab" class="tab-content hidden">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <h3 class="text-2xl font-semibold mb-4">Payment Settings</h3>
                            <p class="text-muted-foreground">Update or manage your connected accounts (e.g., PayPal email,
                                Payoneer ID).</p>
                            {{-- Example settings --}}
                            <ul class="mt-4 space-y-2 text-sm">
                                <li><strong>PayPal Email:</strong> itsabdullah@gmail.com{{-- {{ $user->paypal_email ?? 'Not Set' }} --}}</li>
                                <li><strong>Payoneer ID:</strong> {{-- {{ $user->payoneer_id ?? 'Not Set' }} --}}</li>
                                <li><strong>Wise Account:</strong> {{-- {{ $user->wise_account ?? 'Not Set' }} --}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="withdrawModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <h2 class="text-xl font-semibold mb-2">Withdraw to PayPal</h2>
                <p class="text-sm text-muted-foreground mb-4">Enter the amount you'd like to withdraw. <br>Available:
                    <strong>{{-- ${{ number_format($balance, 2) }} --}}</strong>
                </p>

                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="1" max=""
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Confirm
                            Withdrawal</button>
                    </div>
                </form>

                <button class="absolute top-2 right-3 text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    ✕
                </button>
            </div>
        </div>

    </main>

    {{-- JS to handle tabs --}}
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active-tab'));

            document.getElementById(tabId).classList.remove('hidden');
            event.target.classList.add('active-tab');
        }

        function openModal() {
            document.getElementById('withdrawModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }
    </script>

    {{-- Some tailwind helpers --}}
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
            @apply inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 w-full bg-primary/10 text-primary hover:bg-primary/20 border border-primary;
        }
    </style>
@endsection
