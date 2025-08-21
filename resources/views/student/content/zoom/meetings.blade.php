@extends('student.master.master')
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
        // document.getElementById('meeting_type').addEventListener('change', function() {
        //     let type = this.value;
        //     let container = document.getElementById('students-container');

        //     container.innerHTML = '<p class="text-muted">Loading students...</p>';

        //     if (type !== '') {
        //         fetch(`/teacher/zoom/students/${type}`)
        //             .then(res => res.json())
        //             .then(data => {
        //                 if (data.length > 0) {
        //                     let html = `
    //                 <div class="mb-2">
    //                     <input type="checkbox" id="select_all_students">
    //                     <label for="select_all_students" class="fw-bold">Select All Students</label>
    //                 </div>
    //                 <label class="mb-2 d-block">Select Attendees</label>
    //             `;
        //                     data.forEach(student => {
        //                         html += `
    //                     <div class="d-flex align-items-center mb-2">
    //                         <input type="checkbox" name="attendees[]" value="${student.email}" 
    //                             id="attendee_${student.id}" class="student-checkbox">
    //                         <label for="attendee_${student.id}" style="cursor: pointer; margin-left: 8px;">
    //                             ${student.name} (${student.email})
    //                         </label>
    //                     </div>
    //                 `;
        //                     });
        //                     container.innerHTML = html;

        //                     let selectAll = document.getElementById('select_all_students');
        //                     let checkboxes = container.querySelectorAll('.student-checkbox');

        //                     // When "Select All" is toggled
        //                     selectAll.addEventListener('change', function() {
        //                         checkboxes.forEach(cb => cb.checked = this.checked);
        //                     });

        //                     // When individual checkbox changes
        //                     checkboxes.forEach(cb => {
        //                         cb.addEventListener('change', function() {
        //                             if (!this.checked) {
        //                                 selectAll.checked =
        //                                     false; // uncheck "Select All" if one unchecked
        //                             } else if ([...checkboxes].every(c => c.checked)) {
        //                                 selectAll.checked =
        //                                     true; // check "Select All" if all checked manually
        //                             }
        //                         });
        //                     });
        //                 } else {
        //                     container.innerHTML = '<p class="text-muted">No attendees found for this type.</p>';
        //                 }
        //             })
        //             .catch(err => {
        //                 container.innerHTML = '<p class="text-danger">Error loading students.</p>';
        //                 console.error(err);
        //             });
        //     } else {
        //         container.innerHTML = '';
        //     }
        // });

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
