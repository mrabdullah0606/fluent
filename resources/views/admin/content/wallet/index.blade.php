@extends('admin.master.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('welcome.key_748') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('welcome.key_632') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('welcome.key_749') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{-- Wallet Statistics Cards --}}
        <div class="row m-2">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ __('welcome.key_750') }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span>{{ $balance }}</span>
                                    <span class="text-muted fs-12 ms-1">{{ __('welcome.key_751') }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                    <i class="bx bx-wallet text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ __('welcome.key_368') }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span>{{ $totalEarned }}</span>
                                    <span class="text-muted fs-12 ms-1">{{ __('welcome.key_751') }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bx bx-trending-up text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ __('welcome.key_369') }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span>{{ $totalWithdrawn }}</span>
                                    <span class="text-muted fs-12 ms-1">{{ __('welcome.key_751') }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                    <i class="bx bx-download text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="row m-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">{{ __('welcome.key_752') }}</h5>
                        <div>
                            <a href="{{ route('admin.wallet.transactions') }}" class="btn btn-sm btn-primary">
                                <i class="ri-eye-line align-middle"></i> {{ __('welcome.key_753') }}
                            </a>

                        </div>
                    </div>
                    <div class="card-body">
                        @if ($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table id="userTable" class="table table-hover table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('welcome.key_384') }}</th>
                                            <th scope="col">{{ __('welcome.key_386') }}</th>
                                            <th scope="col">{{ __('welcome.key_387') }}</th>
                                            <th scope="col">{{ __('welcome.key_385') }}</th>
                                            <th scope="col">{{ __('welcome.key_388') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                                <td>
 @if ($transaction->type == 'credit')
                                                        <span class="badge bg-success-subtle text-success">{{ __('welcome.key_755') }}</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">{{ __('welcome.key_756') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-medium {{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                        {{ $transaction->type == 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($transaction->description, 50) }}</td>
                                                <td>${{ number_format($transaction->balance_after, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-wallet-line display-4 text-muted"></i>
                                <h5 class="mt-3">{{ __('welcome.key_757') }}</h5>
                                <p class="text-muted">{{ __('welcome.key_758') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
