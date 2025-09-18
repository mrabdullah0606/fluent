@extends('student.master.master')
@section('title', 'My Zoom Meetings - FluentAll')
@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-10">
                <h2 class="mb-0"><i class="fas fa-video me-2"></i>My Zoom Meetings</h2>
            </div>
            <div class="col-2 text-end">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#packagesModal">
                    View My Packages
                </button>
            </div>
            <!-- Packages Modal -->
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
                                                {{-- <td>{{ $package->package_summary }}</td> --}}
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
                                <p class="text-muted">You haven’t purchased any packages yet.</p>
                            @endif
                        </div>



                        <script>
                            document.querySelectorAll('.deduct-lesson-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const packageId = this.dataset.id;

                                    fetch(`/lesson-tracking/deduct/${packageId}`, {
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

                                                // Optional: alert/toast
                                                alert(data.message);
                                            } else {
                                                alert(data.error || 'Failed to deduct lesson.');
                                            }
                                        })
                                        .catch(err => console.error('Error deducting lesson:', err));
                                });
                            });
                        </script>


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
                        <table class="table table-striped table-bordered align-middle">
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
                                    {{-- @dd($meeting->toArray()) --}}
                                    <tr>
                                        {{-- <td>{{ $meeting->topic }} - {{ $meeting->meeting_type }} </td> --}}
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
                                                {{-- Already joined → show Rejoin --}}
                                                <a href="{{ $meeting->join_url }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    Rejoin
                                                </a>
                                            @else
                                                {{-- First time → normal join (deducts) --}}
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
