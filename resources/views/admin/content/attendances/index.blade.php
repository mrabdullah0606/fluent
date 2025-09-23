@extends('admin.master.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('welcome.key_631') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('welcome.key_632') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('welcome.key_633') }}</li>
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
                        <form method="GET" action="{{ route('admin.attendances.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('welcome.key_521') }}</label>
                                    <select class="form-select" name="teacher_id">
                                        <option value="">{{ __('welcome.key_634') }}</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
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
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('welcome.key_637') }}</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">{{ __('welcome.key_638') }}</button>
                                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">{{ __('welcome.key_639') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Attendances --}}
        <div class="row m-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('welcome.key_640') }}</h5>
                        <button class="btn btn-success" onclick="bulkApprove()">
                            <i class="ri-check-double-line me-1"></i>{{ __('welcome.key_641') }}
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($pendingAttendances->count() > 0)
                            <form id="bulkApproveForm" method="POST"
                                action="{{ route('admin.attendances.bulk-approve') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="selectAll">
                                                </th>
                                                <th>{{ __('welcome.key_384') }}</th>
                                                <th>{{ __('welcome.key_642') }}</th>
                                                <th>{{ __('welcome.key_521') }}</th>
                                                <th>{{ __('welcome.key_643') }}</th>
                                                <th>{{ __('welcome.key_387') }}</th>
                                                <th>{{ __('welcome.key_644') }}</th>
                                                <th>{{ __('welcome.key_604') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingAttendances as $attendance)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="attendance_ids[]"
                                                            value="{{ $attendance->id }}" class="attendance-checkbox">
                                                    </td>
                                                    <td>{{ $attendance->created_at->format('M d, Y H:i') }}</td>
                                                    <td>{{ $attendance->student->name }}</td>
                                                    <td>{{ $attendance->teacher->name }}</td>
                                                    <td>
                                                        {{ $attendance->meeting->topic ?? 'Lesson' }}
                                                        <br><small class="text-muted">ID:
                                                            {{ $attendance->meeting_id }}</small>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-medium">${{ number_format($attendance->base_price, 2) }}</span>
                                                        <br><small class="text-muted">Teacher:
                                                            ${{ number_format($attendance->teacher_earning, 2) }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <span
                                                                class="badge {{ $attendance->student_attended ? 'bg-success' : 'bg-danger' }}">
                                                                Student:
                                                                {{ $attendance->student_attended ? 'Present' : 'Absent' }}
                                                            </span>
                                                            <span
                                                                class="badge {{ $attendance->teacher_attended ? 'bg-success' : 'bg-danger' }}">
                                                                Teacher:
                                                                {{ $attendance->teacher_attended ? 'Present' : 'Absent' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            @if ($attendance->student_attended && $attendance->teacher_attended)
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    onclick="approveAttendance({{ $attendance->id }})">
                                                                    <i class="ri-check-line me-1"></i>{{ __('welcome.key_646') }}
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="rejectAttendance({{ $attendance->id }})">
                                                                <i class="ri-close-line me-1"></i>{{ __('welcome.key_647') }}
                                                            </button>
                                                            <a href="{{ route('admin.attendances.show', $attendance->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="ri-eye-line me-1"></i>{{ __('welcome.key_648') }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>

                            <div class="mt-3">
                                {{ $pendingAttendances->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-checkbox-circle-line display-4 text-muted"></i>
                                <h5 class="mt-3">{{ __('welcome.key_649') }}</h5>
                                <p class="text-muted">{{ __('welcome.key_650') }}</p>
                            </div>
                        @endif
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
                    <h5 class="modal-title" id="modalTitle">{{ __('welcome.key_651') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="approvalForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_652') }}</label>
                            <textarea class="form-control" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('welcome.key_262') }}</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="ri-check-line me-1"></i>{{ __('welcome.key_646') }}
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
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
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
        // Select all checkboxes
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.attendance-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Approve attendance
        function approveAttendance(id) {
            document.getElementById('approvalForm').action = `/admin/attendances/${id}/approve`;
            document.getElementById('modalTitle').textContent = 'Approve Attendance';
            document.getElementById('submitBtn').innerHTML = '<i class="ri-check-line me-1"></i>Approve';
            document.getElementById('submitBtn').className = 'btn btn-success';
            new bootstrap.Modal(document.getElementById('approvalModal')).show();
        }

        // Reject attendance
        function rejectAttendance(id) {
            document.getElementById('rejectForm').action = `/admin/attendances/${id}/reject`;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        // Bulk approve
        function bulkApprove() {
            const checkedBoxes = document.querySelectorAll('.attendance-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one attendance to approve.');
                return;
            }

            if (confirm(`Are you sure you want to approve ${checkedBoxes.length} attendance records?`)) {
                document.getElementById('bulkApproveForm').submit();
            }
        }
    </script>

@endsection
