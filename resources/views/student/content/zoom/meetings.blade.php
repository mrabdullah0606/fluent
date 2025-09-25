@extends('student.master.master')
@section('title', 'My Zoom Meetings - FluentAll')
@section('content')
    <div class="container py-4">
        <!-- Attendance Confirmation Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendanceModalLabel">Confirm Lesson Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="attendanceDetails">
                            <p><strong>Lesson:</strong> <span id="lessonTopic"></span></p>
                            <p><strong>Teacher:</strong> <span id="teacherName"></span></p>
                            <p><strong>Date:</strong> <span id="lessonDate"></span></p>
                            <p><strong>Amount:</strong> $<span id="lessonAmount"></span></p>
                        </div>
                        <hr>
                        <h6>Please confirm attendance:</h6>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="studentAttended">
                            <label class="form-check-label" for="studentAttended">
                                <strong>I attended this lesson</strong>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="teacherAttended">
                            <label class="form-check-label" for="teacherAttended">
                                <strong>My teacher attended this lesson</strong>
                            </label>
                        </div>
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle"></i> Teacher payment will only be processed if both you and
                                your teacher attended the lesson.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAttendanceBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-10">
                <h2 class="mb-0"><i class="fas fa-video me-2"></i>My Zoom Meetings</h2>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#packagesModal">
                    View My Packages
                </button>
            </div>
            <div class="modal fade" id="packagesModal" tabindex="-1" aria-labelledby="packagesModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="packagesModalLabel">My Purchased Packages</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($lessonTracking->count() > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Package</th>
                                            <th>Total Lessons</th>
                                            <th>Taken</th>
                                            <th>Remaining</th>
                                            <th>Price per Lesson</th>
                                            <th>Purchase Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lessonTracking as $package)
                                            <tr>
                                                <td>{{ $package->package_summary }} - {{ $package->teacher_name }}</td>
                                                <td>{{ $package->total_lessons_purchased }}</td>
                                                <td id="taken-{{ $package->id }}">{{ $package->lessons_taken }}</td>
                                                <td id="remaining-{{ $package->id }}">{{ $package->lessons_remaining }}
                                                </td>
                                                <td>${{ number_format($package->price_per_lesson, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($package->purchase_date)->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning deduct-lesson-btn"
                                                        data-id="{{ $package->id }}"
                                                        {{ $package->lessons_remaining <= 0 ? 'disabled' : '' }}>
                                                        Deduct
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">You haven't purchased any packages yet.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success') || session('error'))
            <div class="row mb-3">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Pending Attendance Alert -->
        @if (isset($pendingAttendances) && $pendingAttendances->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attendance Confirmation Required!</strong>
                        You have {{ $pendingAttendances->count() }} lesson(s) that need attendance confirmation.
                        <button type="button" class="btn btn-sm btn-outline-dark ms-2" onclick="showPendingAttendances()">
                            Review Now
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Remaining Lessons</h5>
                        <p class="h3">{{ $remaining }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Taken Lessons</h5>
                        <p class="h3">{{ $lessonsTaken }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center border-dark">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Total Lessons Purchased</h5>
                        <p class="h3">{{ $totalPurchased }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if ($meetings->count() > 0)
                    <div class="table-responsive">
                        <table id="zoomTable" class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Topic</th>
                                    <th>Teacher</th>
                                    <th>Start Time</th>
                                    <th>Duration</th>
                                    <th>Meeting ID</th>
                                    <th>Password</th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetings as $meeting)
                                    <tr>
                                        <td>
                                            {{ $meeting->topic }} -
                                            @php
                                                $attendee = $meeting->attendees->firstWhere('id', auth()->id());
                                                $tracking = null;
                                                if ($attendee?->pivot?->lesson_tracking_id) {
                                                    $tracking = $lessonTracking->firstWhere(
                                                        'id',
                                                        $attendee->pivot->lesson_tracking_id,
                                                    );
                                                }
                                            @endphp
                                            {{ $tracking->package_summary ?? ucfirst($meeting->meeting_type) }}
                                        </td>
                                        <td>{{ $meeting->teacher->name ?? 'N/A' }}</td>
                                        <td>{{ $meeting->start_time->format('d M Y, h:i A') }}</td>
                                        <td>{{ $meeting->duration }} min</td>
                                        <td><code>{{ $meeting->meeting_id }}</code></td>
                                        <td>{{ $meeting->password ?? '-' }}</td>
                                        <td>
                                            @php
                                                $attendee = $meeting->attendees->firstWhere('id', auth()->id());
                                            @endphp
                                            @if ($attendee && $attendee->pivot->has_joined)
                                                <a href="{{ $meeting->join_url }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    Rejoin
                                                </a>
                                            @else
                                                <a href="{{ route('student.zoom.join', $meeting->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    Join
                                                </a>
                                            @endif

                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="copyMeetingLink('{{ $meeting->join_url }}', '{{ $meeting->meeting_id }}', '{{ $meeting->password }}')">
                                                Copy
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="fas fa-video-slash fa-2x mb-2 text-muted"></i>
                        <p class="mb-0">No meetings found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        let currentAttendanceId = null;

        // Check for pending attendances on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkPendingAttendances();
        });

        // Function to check and show pending attendances
        function checkPendingAttendances() {
            fetch('/student/lesson-tracking/pending-attendances')
                .then(response => response.json())
                .then(data => {
                    if (data.attendances && data.attendances.length > 0) {
                        showAttendanceModal(data.attendances[0]);
                    }
                })
                .catch(error => console.error('Error checking pending attendances:', error));
        }

        // Function to show all pending attendances
        function showPendingAttendances() {
            fetch('/student/lesson-tracking/pending-attendances')
                .then(response => response.json())
                .then(data => {
                    if (data.attendances && data.attendances.length > 0) {
                        showAttendanceModal(data.attendances[0]);
                    } else {
                        alert('No pending attendance confirmations found.');
                    }
                })
                .catch(error => console.error('Error loading pending attendances:', error));
        }

        // Function to show attendance modal
        function showAttendanceModal(attendance) {
            currentAttendanceId = attendance.id;

            document.getElementById('lessonTopic').textContent = attendance.meeting_topic;
            document.getElementById('teacherName').textContent = attendance.teacher_name;
            document.getElementById('lessonDate').textContent = attendance.meeting_date;
            document.getElementById('lessonAmount').textContent = parseFloat(attendance.amount).toFixed(2);

            // Reset checkboxes
            document.getElementById('studentAttended').checked = false;
            document.getElementById('teacherAttended').checked = false;

            // Show modal
            new bootstrap.Modal(document.getElementById('attendanceModal')).show();
        }

        // Handle attendance confirmation
        document.getElementById('confirmAttendanceBtn').addEventListener('click', function() {
            const studentAttended = document.getElementById('studentAttended').checked;
            const teacherAttended = document.getElementById('teacherAttended').checked;

            if (!currentAttendanceId) {
                alert('No attendance record selected.');
                return;
            }

            const confirmBtn = this;
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Processing...';

            fetch(`/student/lesson-tracking/confirm-attendance/${currentAttendanceId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_attended: studentAttended,
                        teacher_attended: teacherAttended
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('attendanceModal')).hide();

                        // Check for more pending attendances
                        setTimeout(checkPendingAttendances, 1000);
                    } else {
                        alert(data.error || 'Failed to confirm attendance.');
                    }
                })
                .catch(error => {
                    console.error('Error confirming attendance:', error);
                    alert('Something went wrong. Please try again.');
                })
                .finally(() => {
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Confirm';
                });
        });

        // Existing functions
        document.querySelectorAll('.deduct-lesson-btn').forEach(button => {
            button.addEventListener('click', function() {
                const packageId = this.dataset.id;

                fetch(`/student/lesson-tracking/deduct/${packageId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`remaining-${packageId}`).textContent = data
                                .lessons_remaining;
                            document.getElementById(`taken-${packageId}`).textContent = data
                                .lessons_taken;
                            if (data.lessons_remaining <= 0) button.disabled = true;
                            alert(data.message);
                        } else {
                            alert(data.error || 'Failed to deduct lesson.');
                        }
                    })
                    .catch(err => console.error('Error deducting lesson:', err));
            });
        });

        function copyMeetingLink(joinUrl, meetingId, password) {
            let text = `Zoom Meeting Details:\n\nJoin URL: ${joinUrl}\nMeeting ID: ${meetingId}`;
            if (password) text += `\nPassword: ${password}`;

            navigator.clipboard.writeText(text).then(() => {
                alert("Meeting details copied to clipboard!");
            }).catch(() => {
                prompt("Copy these meeting details:", text);
            });
        }
    </script>
@endsection
