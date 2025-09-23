@extends('admin.master.master')
@section('title', 'Withdrawal Management')

@section('content')
    <main class="main-content" id="main-content">
        <div class="container-fluid py-4">
            {{-- Page Heading --}}
            <div class="d-flex align-items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="me-3" width="40" height="40" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
                </svg>
                <h1 class="display-6 fw-bold text-dark mb-0">{{ __('welcome.key_772') }}</h1>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-x-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Filters --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.wallet.withdrawals.index') }}" class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <label for="status" class="form-label">{{ __('welcome.key_773') }}</label>
                            <select name="status" id="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('welcome.key_774') }}
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('welcome.key_627') }}
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    {{ __('welcome.key_775') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('welcome.key_776') }}
                                </option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('welcome.key_777') }}</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('welcome.key_778') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                {{ __('welcome.key_779') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'warning', 'icon' => 'clock'],
                        'processing' => ['bg' => 'info', 'icon' => 'arrow-repeat'],
                        'completed' => ['bg' => 'success', 'icon' => 'check-circle'],
                        'failed' => ['bg' => 'danger', 'icon' => 'x-circle'],
                    ];
                @endphp
                @foreach (['pending', 'processing', 'completed', 'failed'] as $status)
                    <div class="col-6 col-md-3">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-{{ $statusConfig[$status]['bg'] }} bg-opacity-10 rounded p-3 me-3">
                                    <i
                                        class="bi bi-{{ $statusConfig[$status]['icon'] }} text-{{ $statusConfig[$status]['bg'] }} fs-4"></i>
                                </div>
                                <div>
                                    <p class="card-text text-muted mb-1 text-capitalize">{{ $status }}</p>
                                    <h4 class="card-title fw-bold mb-0">
                                        {{ $withdrawals->where('status', $status)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Withdrawals Table --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    @foreach (['ID', 'Teacher', 'Amount', 'Method', 'Status', 'Date', 'Actions'] as $heading)
                                        <th scope="col" class="fw-semibold">{{ $heading }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdrawals as $withdrawal)
                                    {{-- @forelse($withdrawals as $withdrawal) --}}
                                    <tr>
                                        {{-- ID --}}
                                        <td class="fw-medium">#{{ $withdrawal->id }}</td>

                                        {{-- Teacher --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img class="rounded-circle border me-3" width="40" height="40"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($withdrawal->teacher->name) }}"
                                                    alt="">
                                                <div>
                                                    <div class="fw-medium">{{ $withdrawal->teacher->name }}</div>
                                                    <div class="text-muted small">{{ $withdrawal->teacher->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Amount --}}
                                        <td class="fw-semibold">${{ number_format($withdrawal->amount, 2) }}</td>

                                        {{-- Method --}}
                                        <td>
                                            <span class="badge bg-info text-capitalize">{{ $withdrawal->method }}</span>
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @php
                                                $statusClass = match ($withdrawal->status) {
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'failed' => 'danger',
                                                    'cancelled' => 'secondary',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} text-capitalize">
                                                {{ $withdrawal->status }}
                                            </span>
                                        </td>

                                        {{-- Date --}}
                                        <td class="text-muted">
                                            {{ $withdrawal->created_at->format('M j, Y') }}<br>
                                            <small>{{ $withdrawal->created_at->format('g:i A') }}</small>
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="viewWithdrawal({{ $withdrawal->id }})">
                                                    {{ __('welcome.key_648') }}
                                                </button>
                                                @if ($withdrawal->status === 'pending')
                                                    <button type="button" class="btn btn-outline-success"
                                                        onclick="approveWithdrawal({{ $withdrawal->id }})">
                                                        {{ __('welcome.key_646') }}
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="rejectWithdrawal({{ $withdrawal->id }})">
                                                        {{ __('welcome.key_647') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            {{ __('welcome.key_781') }}
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($withdrawals->hasPages())
                        <div class="card-footer bg-light">
                            {{ $withdrawals->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
            {{-- -All Transaction History --}}
            {{-- Transactions Table --}}
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('welcome.key_782') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="userTable2" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('welcome.key_384') }}</th>
                                    <th>{{ __('welcome.key_521') }}</th>
                                    <th>{{ __('welcome.key_385') }}</th>
                                    <th>{{ __('welcome.key_386') }}</th>
                                    <th>{{ __('welcome.key_387') }}</th>
                                    <th>{{ __('welcome.key_768') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $txn)
                                    {{-- @forelse($transactions as $txn) --}}
                                    <tr>
                                        <td>
                                            {{ $txn->created_at->format('M j, Y') }}
                                            <br><small class="text-muted">{{ $txn->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            {{ $txn->teacher->name ?? 'N/A' }}
                                            <br><small class="text-muted">ID: {{ $txn->teacher_id }}</small>
                                        </td>
                                        <td>
                                            {{ $txn->description }}
                                            @if ($txn->payment_id)
                                                <br><small class="text-primary">Payment #{{ $txn->payment_id }}</small>
                                            @endif
                                             @if ($txn->withdrawal_id)
                                                <br><small class="text-purple">Withdrawal
                                                    #{{ $txn->withdrawal_id }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $txn->type == 'credit' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($txn->type) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($txn->category) }}</small>
                                        </td>
                                        <td class="fw-bold {{ $txn->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $txn->type == 'credit' ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td>${{ number_format($txn->balance_after, 2) }}</td>
                                    </tr>
                                @endforeach
                                {{-- @empty --}}
                                {{-- <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            {{ __('welcome.key_783') }}
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($transactions->hasPages())
                        <div class="card-footer bg-light">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    {{-- View Withdrawal Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">{{ __('welcome.key_784') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="withdrawal-details">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Withdrawal Modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">{{ __('welcome.key_785') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID <small
                                    class="text-muted">(Optional)</small></label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control"
                                placeholder="Enter external transaction ID">
                        </div>
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">{{ __('welcome.key_678') }} <small
                                    class="text-muted">{{ __('welcome.key_786') }}</small></label>
                            <textarea name="admin_notes" id="admin_notes" rows="3" class="form-control"
                                placeholder="Add any notes about this approval"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('welcome.key_262') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('welcome.key_785') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Withdrawal Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">{{ __('welcome.key_787') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="failure_reason" class="form-label">{{ __('welcome.key_654') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="failure_reason" id="failure_reason" rows="4" required class="form-control"
                                placeholder="Please provide a reason for rejecting this withdrawal request"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('welcome.key_262') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('welcome.key_787') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function viewWithdrawal(id) {
            // Fetch withdrawal details via AJAX
            fetch(`/admin/wallet/withdrawals/${id}`)
                .then(response => response.json())
                .then(data => {
                    const detailsDiv = document.getElementById('withdrawal-details');
                    detailsDiv.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-2">Withdrawal ID</h6>
                                <h4 class="card-title fw-bold">#${data.id}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-2">Amount</h6>
                                <h4 class="card-title fw-bold text-success">$${parseFloat(data.amount).toFixed(2)}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-2">Method</h6>
                                <h5 class="card-title text-capitalize">${data.method}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-2">Status</h6>
                                <span class="badge bg-${getStatusClass(data.status)} fs-6">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Teacher Information</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Name:</strong> ${data.teacher.name}</p>
                                <p class="mb-0"><strong>Email:</strong> ${data.teacher.email}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Account Details</h6>
                            </div>
                            <div class="card-body">
                                ${formatAccountDetails(data.account_details)}
                            </div>
                        </div>
                    </div>
                    
                    ${data.transaction_id ? `
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">Transaction ID</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <code class="bg-secondary bg-opacity-10 p-2 rounded d-block">${data.transaction_id}</code>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ` : ''}
                    
                    ${data.admin_notes ? `
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">Admin Notes</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="alert alert-info mb-0">${data.admin_notes}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ` : ''}
                    
                    ${data.failure_reason ? `
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">Failure Reason</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="alert alert-danger mb-0">${data.failure_reason}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ` : ''}
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Request Date</h6>
                                <p class="mb-0">${new Date(data.created_at).toLocaleDateString()} ${new Date(data.created_at).toLocaleTimeString()}</p>
                            </div>
                        </div>
                    </div>
                    ${data.processed_date ? `
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h6 class="text-muted mb-2">Processed Date</h6>
                                                                    <p class="mb-0">${new Date(data.processed_date).toLocaleDateString()} ${new Date(data.processed_date).toLocaleTimeString()}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ` : ''}
                </div>
            `;
                    new bootstrap.Modal(document.getElementById('viewModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load withdrawal details.');
                });
        }

        function approveWithdrawal(id) {
            document.getElementById('approveForm').action = `/admin/wallet/withdrawals/${id}/approve`;
            new bootstrap.Modal(document.getElementById('approveModal')).show();
        }

        function rejectWithdrawal(id) {
            document.getElementById('rejectForm').action = `/admin/wallet/withdrawals/${id}/reject`;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        function getStatusClass(status) {
            const classes = {
                'pending': 'warning',
                'processing': 'info',
                'completed': 'success',
                'failed': 'danger',
                'cancelled': 'secondary'
            };
            return classes[status] || 'secondary';
        }

        function formatAccountDetails(details) {
            if (!details) return '<p class="text-muted mb-0">No account details available</p>';

            let html = '';
            Object.keys(details).forEach(key => {
                html +=
                    `<p class="mb-1"><strong>${key.replace('_', ' ').toUpperCase()}:</strong> ${details[key]}</p>`;
            });
            return html;
        }
    </script>
@endsection
