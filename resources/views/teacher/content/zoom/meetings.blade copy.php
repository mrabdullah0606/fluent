@extends('teacher.master.master')
@section('title', 'Lessons - FluentAll')
@section('content')
    <div class="dashboard__content-wrap container">
        <div class="dashboard__content-title mt-4 mb-4 bg-warning p-3">
            <h4 class="title text-white fw-bold">{{ __('Zoom Meetings') }}</h4>
        </div>

        {{-- Toast Alerts --}}
        @if (session('success') || session('error'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Main Form & Instructions --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm p-4">
                    <form method="POST" action="{{ route('teacher.zoom.meetings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="topic" class="form-label">{{ __('Meeting Topic') }} <code>*</code></label>
                            <input type="text" id="topic" name="topic" class="form-control"
                                value="{{ old('topic') }}" required>
                            @error('topic')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Type Dropdown --}}
                        <div class="mb-3">
                            <label for="meeting_type" class="form-label">{{ __('Meeting Type') }} <code>*</code></label>
                            <select id="meeting_type" name="meeting_type" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                <option value="package">Package</option>
                                <option value="duration">Duration</option>
                                <option value="group">Group</option>
                            </select>
                        </div>

                        {{-- Summary Dropdown (loads after type is chosen) --}}
                        <div class="mb-3">
                            <label for="meeting_summary" class="form-label">{{ __('Summary') }} <code>*</code></label>
                            <select id="meeting_summary" name="meeting_summary" class="form-control" required disabled>
                                <option value="">-- Select Summary --</option>
                            </select>
                        </div>

                        {{-- Students List --}}
                        <div id="students-container" class="mt-3"></div>



                        <div class="mb-3">
                            <label for="start_time" class="form-label">{{ __('Start Time') }} <code>*</code></label>
                            <input type="datetime-local" id="start_time" name="start_time" class="form-control"
                                value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">{{ __('Duration (minutes)') }} <code>*</code></label>
                            <input type="number" id="duration" name="duration" class="form-control"
                                value="{{ old('duration', 30) }}" min="1" max="480" required>
                            @error('duration')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">{{ __('Create Meeting') }}</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-light p-4 shadow-sm">
                    <h5 class="mb-3">{{ __('Quick Instructions') }}</h5>
                    <ul class="mb-0 ps-3">
                        <li>Enter a descriptive meeting topic</li>
                        <li>Select your preferred start time</li>
                        <li>Set duration (max 8 hours)</li>
                        <li>Meeting link will appear below after creation</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Meeting List --}}
        <div class="row mt-5">
            <div class="col-12">
                <h5 class="mb-3">{{ __('Your Meetings') }}</h5>
                @if ($meetings->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="zoomTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Topic') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Start Time') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Meeting ID') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->topic }}</td>
                                        <td>
                                            {{ $meeting->creator?->name ?? 'N/A' }}
                                            @if ($meeting->created_by === auth()->id())
                                                <span class="badge bg-primary ms-1">You</span>
                                            @endif
                                        </td>
                                        <td>{{ $meeting->start_time->format('d M Y, h:i A') }}</td>
                                        <td>{{ $meeting->duration }} {{ __('min') }}</td>
                                        <td>
                                            <code>{{ $meeting->meeting_id }}</code>
                                            @if ($meeting->password)
                                                <br><small class="text-muted">Password:
                                                    <code>{{ $meeting->password }}</code></small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ $meeting->join_url }}" target="_blank"
                                                class="btn btn-sm btn-success">{{ __('Join') }}</a>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="copyToClipboard('{{ $meeting->join_url }}')">
                                                {{ __('Copy') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $meetings->links() }}
                @else
                    <div class="alert alert-light text-center mt-4">
                        <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
                        <h6>{{ __('No meetings found') }}</h6>
                        <p class="text-muted">{{ __('Create your first Zoom meeting using the form above.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let typeSelect = document.getElementById('meeting_type');
        let summarySelect = document.getElementById('meeting_summary');
        let studentsContainer = document.getElementById('students-container');

        // When type changes → load summaries
        typeSelect.addEventListener('change', function() {
            let type = this.value;
            summarySelect.innerHTML = '<option value="">-- Select Summary --</option>';
            studentsContainer.innerHTML = '';

            if (type) {
                summarySelect.disabled = true;
                fetch(`/teacher/zoom/summaries?type=${encodeURIComponent(type)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(summary => {
                                summarySelect.innerHTML +=
                                    `<option value="${summary}">${summary}</option>`;
                            });
                            summarySelect.disabled = false;
                        } else {
                            summarySelect.innerHTML = '<option value="">No summaries found</option>';
                        }
                    });
            } else {
                summarySelect.disabled = true;
            }
        });

        // When summary changes → load students
        summarySelect.addEventListener('change', function() {
            let summary = this.value;
            studentsContainer.innerHTML = '';

            if (summary) {
                studentsContainer.innerHTML = '<p class="text-muted">Loading students...</p>';
                fetch(`/teacher/zoom/students?summary=${encodeURIComponent(summary)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = `
                        <div class="mb-2">
                            <input type="checkbox" id="select_all_students">
                            <label for="select_all_students" class="fw-bold">Select All Students</label>
                        </div>
                        <label class="mb-2 d-block">Select Attendees</label>
                    `;
                            data.forEach(student => {
                                html += `
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" name="attendees[]" value="${student.email}" 
                                    id="attendee_${student.id}" class="student-checkbox">
                                <label for="attendee_${student.id}" style="cursor: pointer; margin-left: 8px;">
                                    ${student.name} (${student.email})
                                </label>
                            </div>
                        `;
                            });
                            studentsContainer.innerHTML = html;

                            let selectAll = document.getElementById('select_all_students');
                            let checkboxes = studentsContainer.querySelectorAll('.student-checkbox');

                            selectAll.addEventListener('change', function() {
                                checkboxes.forEach(cb => cb.checked = this.checked);
                            });

                            checkboxes.forEach(cb => {
                                cb.addEventListener('change', function() {
                                    if (!this.checked) {
                                        selectAll.checked = false;
                                    } else if ([...checkboxes].every(c => c.checked)) {
                                        selectAll.checked = true;
                                    }
                                });
                            });
                        } else {
                            studentsContainer.innerHTML =
                                '<p class="text-muted">No students found for this summary.</p>';
                        }
                    });
            }
        });

        // Copy link to clipboard with toast
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
                toast.innerHTML = '<i class="fas fa-check-circle me-1"></i> {{ __('Link copied to clipboard!') }}';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }).catch(err => {
                alert('Failed to copy link.');
            });
        }

        // Set minimum datetime
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('start_time');
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            input.min = now.toISOString().slice(0, 16);
        });
    </script>
@endpush
