@extends('admin.master.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('welcome.key_759') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('welcome.key_632') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.wallet.index') }}">{{ __('welcome.key_749') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('welcome.key_760') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row m-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.wallet.transactions') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('welcome.key_386') }}</label>
                                    <select class="form-select" name="type">
                                        <option value="">{{ __('welcome.key_761') }}</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>{{ __('welcome.key_755') }}
                                        </option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>{{ __('welcome.key_756') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('welcome.key_762') }}</label>
                                    <select class="form-select" name="category">
                                        <option value="">{{ __('welcome.key_763') }}</option>
                                        <option value="commission"
                                            {{ request('category') == 'commission' ? 'selected' : '' }}>{{ __('welcome.key_764') }}</option>
                                        <option value="withdrawal"
                                            {{ request('category') == 'withdrawal' ? 'selected' : '' }}>{{ __('welcome.key_765') }}</option>
                                        <option value="refund" {{ request('category') == 'refund' ? 'selected' : '' }}>
                                            {{ __('welcome.key_766') }}</option>
                                        <option value="adjustment"
                                            {{ request('category') == 'adjustment' ? 'selected' : '' }}>{{ __('welcome.key_767') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('welcome.key_635') }}</label>
                                    <input type="date" class="form-control" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('welcome.key_636') }}</label>
                                    <input type="date" class="form-control" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('welcome.key_637') }}</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">{{ __('welcome.key_638') }}</button>
                                        <a href="{{ route('admin.wallet.transactions') }}"
                                            class="btn btn-secondary">{{ __('welcome.key_639') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="row m-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('welcome.key_371') }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($transactions->count() > 0)
                            <div class="table-responsive">
                                <table id="userTable" class="table table-hover align-middle table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('welcome.key_384') }}</th>
                                            <th scope="col">{{ __('welcome.key_386') }}</th>
                                            <th scope="col">{{ __('welcome.key_762') }}</th>
                                            <th scope="col">{{ __('welcome.key_387') }}</th>
                                            <th scope="col">{{ __('welcome.key_385') }}</th>
                                            <th scope="col">{{ __('welcome.key_768') }}</th>
                                            <th scope="col">{{ __('welcome.key_769') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    @if ($transaction->type == 'credit')
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-arrow-down-line me-1"></i>{{ __('welcome.key_755') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">
                                                            <i class="ri-arrow-up-line me-1"></i>{{ __('welcome.key_756') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ ucfirst($transaction->category) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-medium {{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                        {{ $transaction->type == 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($transaction->description, 60) }}</td>
                                                <td class="fw-medium">${{ number_format($transaction->balance_after, 2) }}
                                                </td>
                                                <td>
                                                    @if ($transaction->reference_id)
                                                        <code>{{ $transaction->reference_id }}</code>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-file-list-3-line display-4 text-muted"></i>
                                <h5 class="mt-3">{{ __('welcome.key_391') }}</h5>
                                <p class="text-muted">{{ __('welcome.key_771') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
