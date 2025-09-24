@extends('admin.master.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('welcome.key_655') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('welcome.key_632') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.attendances.index') }}">{{ __('welcome.key_656') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('welcome.key_657') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row m-3">
            <div class="col-lg-8">
                {{-- Attendance Information --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-calendar-check-line me-2"></i>{{ __('welcome.key_658') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <h6 class="text-muted mb-2">{{ __('welcome.key_659') }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-sm me-3">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                {{ strtoupper(substr($attendance->student->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $attendance->student->name }}</h6>
                                            <small class="text-muted">{{ $attendance->student->email }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span
                                            class="badge {{ $attendance->student_attended ? 'bg-success' : 'bg-danger' }} fs-6">
                                            <i class="ri-user-line me-1"></i>
                                            {{ $attendance->student_attended ? 'Present' : 'Absent' }}
                                        </span>
                                        @if ($attendance->student_confirmed_at)
                                            <div class="text-muted small mt-1">
                                                <i class="ri-time-line me-1"></i>
                                                Confirmed: {{ $attendance->student_confirmed_at->format('M d, Y h:i A') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <h6 class="text-muted mb-2">{{ __('welcome.key_661') }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-sm me-3">
                                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                {{ strtoupper(substr($attendance->teacher->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $attendance->teacher->name }}</h6>
                                            <small class="text-muted">{{ $attendance->teacher->email }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span
                                            class="badge {{ $attendance->teacher_attended ? 'bg-success' : 'bg-danger' }} fs-6">
                                            <i class="ri-user-line me-1"></i>
                                            {{ $attendance->teacher_attended ? 'Present' : 'Absent' }}
                                        </span>
                                        @if ($attendance->teacher_confirmed_at)
                                            <div class="text-muted small mt-1">
                                                <i class="ri-time-line me-1"></i>
                                                Confirmed: {{ $attendance->teacher_confirmed_at->format('M d, Y h:i A') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Meeting Details --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-video-line me-2"></i>{{ __('welcome.key_663') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium" style="width: 150px;">{{ __('welcome.key_664') }}</td>
                                        <td>{{ $attendance->meeting->topic ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">{{ __('welcome.key_665') }}</td>
                                        <td><code>{{ $attendance->meeting->meeting_id ?? 'N/A' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">{{ __('welcome.key_666') }}</td>
                                        <td>{{ $attendance->meeting->start_time->format('M d, Y h:i A') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">{{ __('welcome.key_667') }}</td>
                                        <td>{{ $attendance->meeting->duration ?? 'N/A' }} minutes</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">{{ __('welcome.key_668') }}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                {{ ucfirst($attendance->meeting->meeting_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                @if ($attendance->payment)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-money-dollar-circle-line me-2"></i>{{ __('welcome.key_670') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium" style="width: 150px;">{{ __('welcome.key_671') }}</td>
                                            <td><code>{{ $attendance->payment_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('welcome.key_672') }}</td>
                                            <td>{{ $attendance->payment->summary ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('welcome.key_673') }}</td>
                                            <td class="fw-bold text-primary">
                                                ${{ number_format($attendance->payment->total ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('welcome.key_674') }}</td>
                                            <td>${{ number_format($attendance->base_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('welcome.key_675') }}</td>
                                            <td class="text-success fw-medium">
                                                ${{ number_format($attendance->teacher_earning, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('welcome.key_676') }}</td>
                                            <td class="text-warning fw-medium">
                                                ${{ number_format($attendance->admin_commission, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Admin Notes (if any) --}}
               @if ($attendance->admin_notes)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-text-line me-2"></i>{{ __('welcome.key_678') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="ri-information-line me-2"></i>
                                {{ $attendance->admin_notes }}
                            </div>
                             @if ($attendance->approvedBy)
                                <small class="text-muted">
                                    <i class="ri-user-line me-1"></i>
                                    By: {{ $attendance->approvedBy->name }} |
                                    <i class="ri-time-line me-1"></i>
                                    {{ $attendance->admin_approved_at->format('M d, Y h:i A') }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Status & Actions Card --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-settings-line me-2"></i>{{ __('welcome.key_680') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Current Status --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('welcome.key_681') }}</h6>
                            <div class="d-flex flex-column gap-2">
                                <span class="badge bg-primary-subtle text-primary fs-6">
                                    Attendance: {{ ucfirst($attendance->status) }}
                                </span>

                                 @if ($attendance->admin_status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning fs-6">
                                        {{ __('welcome.key_683') }}
                                    </span>
                                @elseif($attendance->admin_status === 'approved')
                                    <span class="badge bg-success-subtle text-success fs-6">
                                        {{ __('welcome.key_685') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fs-6">
                                        {{ __('welcome.key_686') }}
                                    </span>
                                @endif

                                @if ($attendance->payment_released)
                                    <span class="badge bg-success-subtle text-success fs-6">
                                        {{ __('welcome.key_688') }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="ri-time-line me-1"></i>
                                        {{ $attendance->payment_released_at->format('M d, Y h:i A') }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary fs-6">
                                        {{ __('welcome.key_689') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        @if ($attendance->admin_status === 'pending')
                            <div class="d-grid gap-2">
                                 @if ($attendance->student_attended && $attendance->teacher_attended)
                                    <button class="btn btn-success" onclick="approveAttendance()">
                                        <i class="ri-check-double-line me-1"></i>{{ __('welcome.key_690') }}
                                    </button>
                                @endif
                                <button class="btn btn-danger" onclick="rejectAttendance()">
                                    <i class="ri-close-line me-1"></i>{{ __('welcome.key_647') }}
                                </button>
                            </div>

                            @if (!$attendance->student_attended || !$attendance->teacher_attended)
                                <div class="alert alert-warning mt-3">
                                    <i class="ri-alert-line me-2"></i>
                                    <strong>{{ __('welcome.key_376') }}</strong> Payment cannot be processed because
                                    @if (!$attendance->student_attended && !$attendance->teacher_attended)
                                        both parties marked as absent.
                                    @elseif(!$attendance->student_attended)
                                        student is marked as absent.
                                    @else
                                        teacher is marked as absent.
                                    @endif
                                </div>
                            @endif
                        @endif

                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="ri-arrow-left-line me-1"></i>{{ __('welcome.key_691') }}
                        </a>
                    </div>
                </div>

                {{-- Timeline Card --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-time-line me-2"></i>{{ __('welcome.key_692') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">{{ __('welcome.key_693') }}</h6>
                                    <p class="timeline-desc text-muted">
                                        {{ $attendance->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>

                              @if ($attendance->student_confirmed_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">{{ __('welcome.key_694') }}</h6>
                                        <p class="timeline-desc text-muted">
                                            {{ $attendance->student_confirmed_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                           @if ($attendance->teacher_confirmed_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">{{ __('welcome.key_695') }}</h6>
                                        <p class="timeline-desc text-muted">
                                            {{ $attendance->teacher_confirmed_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                             @if ($attendance->admin_approved_at)
                                <div class="timeline-item">
                                    <div
                                        class="timeline-marker {{ $attendance->admin_status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Admin {{ ucfirst($attendance->admin_status) }}</h6>
                                        <p class="timeline-desc text-muted">
                                            {{ $attendance->admin_approved_at->format('M d, Y h:i A') }}
                                            @if ($attendance->approvedBy)
                                                <br>By: {{ $attendance->approvedBy->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                             @if ($attendance->payment_released_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">{{ __('welcome.key_698') }}</h6>
                                        <p class="timeline-desc text-muted">
                                            {{ $attendance->payment_released_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Modal --}}
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('welcome.key_651') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.attendances.approve', $attendance->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            {{ __('welcome.key_699') }}
                            <strong>${{ number_format($attendance->teacher_earning, 2) }}</strong> {{ __('welcome.key_700') }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_652') }}</label>
                            <textarea class="form-control" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('welcome.key_262') }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-check-line me-1"></i>{{ __('welcome.key_701') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('welcome.key_653') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.attendances.reject', $attendance->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>
                            {{ __('welcome.key_702') }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_654') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="admin_notes" rows="3" required
                                placeholder="Please provide a reason for rejecting this attendance..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('welcome.key_262') }}</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-close-line me-1"></i>{{ __('welcome.key_647') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function approveAttendance() {
            new bootstrap.Modal(document.getElementById('approvalModal')).show();
        }

        function rejectAttendance() {
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    </script>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content {
            margin-left: 10px;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .timeline-desc {
            font-size: 12px;
            margin-bottom: 0;
        }
    </style>

@endsection
