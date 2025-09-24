@extends('student.master.master')
@section('title', 'Student Dashboard - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-2">Welcome Back, {{ $student->name }}!</h1>
                    <p class="text-muted-foreground mb-8">{{ __('welcome.key_416') }}</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">{{ __('welcome.key_543') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="p-6 pt-0">
                                <div class="text-2xl font-bold">{{ $totalTeachers ?? 0 }}</div>
                                <p class="text-xs text-muted-foreground">{{ __('welcome.key_544') }}</p>
                            </div>
                        </div>
                    </div>

                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">{{ __('welcome.key_419') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div class="p-6 pt-0">
                                <div class="text-2xl font-bold">{{ $lessonSummary['total_this_week'] }}</div>
                                <p class="text-xs text-muted-foreground">
                                    {{ $lessonSummary['completed'] }} completed, {{ $lessonSummary['upcoming'] }} upcoming
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('welcome.key_422') }}</h2>

                    <div class="space-y-4" id="lessonsContainer">
                        @foreach ($upcomingMeetings as $index => $meeting)
                            @php
                                $hoursDiff = now()->diffInHours(\Carbon\Carbon::parse($meeting['start_time']), false);
                            @endphp

                            <div
                                class="lesson-item rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow m-2 {{ $index >= 4 ? 'hidden' : '' }}">
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                            <span
                                                class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ __('welcome.key_423') }}</span>
                                        </span>
                                        <div>
                                            <p class="font-semibold text-foreground">{{ $meeting['teacher_name'] }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $meeting['topic'] }} -
                                                {{ $meeting['duration'] }} min</p>
                                            <p class="text-sm text-warning font-medium flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($meeting['start_time'])->isToday()
                                                    ? 'Today at ' . \Carbon\Carbon::parse($meeting['start_time'])->format('H:i')
                                                    : \Carbon\Carbon::parse($meeting['start_time'])->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $pivot = DB::table('zoom_meeting_user')
                                                ->where('zoom_meeting_id', $meeting['id'])
                                                ->where('user_id', auth()->id())
                                                ->first();
                                        @endphp

                                        @if ($pivot)
                                            @if ($pivot->status === 'cancelled')
                                                <span class="badge bg-danger">
                                                    Cancelled by
                                                    {{ \App\Models\User::find($pivot->action_by)?->name ?? 'Unknown' }}
                                                </span>
                                            @elseif ($pivot->status === 'rescheduled')
                                                <span class="badge bg-warning text-dark">
                                                    Rescheduled by
                                                    {{ \App\Models\User::find($pivot->action_by)?->name ?? 'Unknown' }}
                                                    <br>
                                                    New Time:
                                                    {{ \Carbon\Carbon::parse($pivot->rescheduled_time)->format('M d, Y H:i') }}
                                                </span>
                                            @elseif ($pivot->has_joined)
                                                <a href="{{ $meeting['join_url'] }}" target="_blank">
                                                    <button
                                                        class="inline-flex items-center justify-center text-sm font-medium bg-primary text-white hover:bg-primary/90 h-9 rounded-md px-3">
                                                        {{ __('welcome.key_547') }}
                                                    </button>
                                                </a>
                                            @else
                                                <a href="{{ route('student.zoom.join', $meeting['id']) }}">
                                                    <button
                                                        class="inline-flex items-center justify-center text-sm font-medium bg-success text-white hover:bg-success/90 h-9 rounded-md px-3">
                                                        {{ __('welcome.key_424') }}
                                                    </button>
                                                </a>
                                            @endif
                                        @endif

                                        {{-- <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                id="lessonOptions{{ $index }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Options
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="lessonOptions{{ $index }}">
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('student.zoom.cancel', $meeting['id']) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('{{ $hoursDiff < 24 ? 'If you cancel the lesson now, it will be paid to the tutor because it is cancelled within the last 24 hours.' : 'Are you sure you want to cancel this lesson?' }}')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                </li>
                                                <hr>
                                                <li>
                                                    @if ($hoursDiff < 24)
                                                        <button class="dropdown-item disabled" type="button">Cannot
                                                            reschedule within 24h</button>
                                                    @else
                                                        <form method="POST"
                                                            action="{{ route('student.zoom.reschedule', $meeting['id']) }}">
                                                            @csrf
                                                            <input type="datetime-local" name="rescheduled_time"
                                                                class="form-control m-2 w-75" required>
                                                            <button type="submit" class="dropdown-item">Reschedule</button>
                                                        </form>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div> --}}
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                id="lessonOptions{{ $index }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                {{ __('welcome.key_548') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="lessonOptions{{ $index }}">
                                                {{-- Cancel Option --}}
                                                <li>
                                                    @if ($pivot && !$pivot->has_joined)
                                                        <form method="POST"
                                                            action="{{ route('student.zoom.cancel', $meeting['id']) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('{{ $hoursDiff < 24 ? 'If you cancel the lesson now, it will be paid to the tutor because it is cancelled within the last 24 hours.' : 'Are you sure you want to cancel this lesson?' }}')">
                                                                {{ __('welcome.key_262') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="dropdown-item disabled" type="button">{{ __('welcome.key_549') }}</button>
                                                    @endif
                                                </li>
                                                <hr>
                                                {{-- Reschedule Option --}}
                                                <li>
                                                    @if ($pivot && !$pivot->has_joined)
                                                        @if ($hoursDiff < 24)
                                                            <button class="dropdown-item disabled" type="button">
                                                                {{ __('welcome.key_550') }}
                                                            </button>
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('student.zoom.reschedule', $meeting['id']) }}">
                                                                @csrf
                                                                <input type="datetime-local" name="rescheduled_time"
                                                                    class="form-control m-2 w-75" required>
                                                                <button type="submit"
                                                                    class="dropdown-item">{{ __('welcome.key_551') }}</button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <button class="dropdown-item disabled" type="button">{{ __('welcome.key_552') }}</button>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($upcomingMeetings) > 4)
                        <div class="text-center mt-6">
                            <button id="loadMoreBtn"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                Show More ({{ count($upcomingMeetings) - 4 }} left)
                            </button>
                        </div>
                    @endif
                </div>

               <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let visibleCount = 4;
                        const items = document.querySelectorAll(".lesson-item");
                        const btn = document.getElementById("loadMoreBtn");

                        if (btn) {
                            btn.addEventListener("click", function() {
                                const nextCount = visibleCount + 4;

                                for (let i = visibleCount; i < nextCount && i < items.length; i++) {
                                    items[i].classList.remove("hidden");
                                }

                                visibleCount = nextCount;
                                const remaining = items.length - visibleCount;

                                if (remaining > 0) {
                                    btn.textContent = `Show More (${remaining} left)`;
                                } else {
                                    btn.style.display = "none";
                                }
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </main>
@endsection
