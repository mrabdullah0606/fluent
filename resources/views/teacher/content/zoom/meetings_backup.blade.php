@extends('teacher.master.master')
@section('content')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Zoom Meetings') }}</h4>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="instructor__profile-form-wrap">
                    <div class="row">
                        {{-- Create Meeting Form --}}
                        <div class="col-xl-8">
                            <form method="POST" action="{{ route('teacher.zoom.meetings.store') }}"
                                class="instructor__profile-form">
                                @csrf
                                <div class="form-grp">
                                    <label for="topic">{{ __('Meeting Topic') }} <code>*</code></label>
                                    <input type="text" id="topic" name="topic" class="form-control"
                                        value="{{ old('topic') }}" required>
                                    @error('topic')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-grp">
                                    <label for="start_time">{{ __('Start Time') }} <code>*</code></label>
                                    <input type="datetime-local" id="start_time" name="start_time" class="form-control"
                                        value="{{ old('start_time') }}" required>
                                    @error('start_time')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-grp">
                                    <label for="duration">{{ __('Duration (minutes)') }} <code>*</code></label>
                                    <input type="number" id="duration" name="duration" class="form-control"
                                        value="{{ old('duration', 30) }}" min="1" max="480" required>
                                    @error('duration')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="submit-btn mt-25">
                                    <button type="submit" class="btn">{{ __('Create Meeting') }}</button>
                                </div>
                            </form>
                        </div>

                        {{-- Info Box --}}
                        <div class="col-xl-4">
                            <div class="alert alert-info">
                                <h5 class="mb-2">{{ __('Quick Instructions') }}</h5>
                                <ul class="mb-0">
                                    <li>Enter a descriptive meeting topic</li>
                                    <li>Select your preferred start time</li>
                                    <li>Set duration (max 8 hours)</li>
                                    <li>Meeting link will appear below after creation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Meeting List --}}
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <h5 class="mb-3">{{ __('Your Meetings') }}</h5>
                            @if ($meetings->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
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
                                                        {{ $meeting->creator ? $meeting->creator->name : 'N/A' }}
                                                        @if ($meeting->created_by === auth()->id())
                                                            <small class="badge bg-primary ms-1">You</small>
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
                                                            class="btn btn-sm btn-primary">
                                                            {{ __('Join Meeting') }}
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            onclick="copyToClipboard('{{ $meeting->join_url }}')">
                                                            {{ __('Copy Link') }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $meetings->links() }}
                            @else
                                <div class="alert alert-light text-center">
                                    <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
                                    <h6>{{ __('No meetings found') }}</h6>
                                    <p class="text-muted">{{ __('Create your first Zoom meeting using the form above.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Copy join link to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
                toast.innerHTML = '<i class="fas fa-check"></i> Meeting link copied to clipboard!';
                document.body.appendChild(toast);

                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Failed to copy link. Please copy manually.');
            });
        }

        // Set minimum datetime to current time
        document.addEventListener('DOMContentLoaded', function() {
            const startTimeInput = document.getElementById('start_time');
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            startTimeInput.min = now.toISOString().slice(0, 16);
        });
    </script>
@endpush
