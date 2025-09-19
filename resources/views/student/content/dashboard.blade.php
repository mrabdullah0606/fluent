@extends('student.master.master')
@section('title', 'Student Dashboard - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-2">Welcome Back, {{ $student->name }}!</h1>
                    <p class="text-muted-foreground mb-8">Here's what's happening on your FluentAll dashboard today.</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Total Teachers</h3>
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
                                <p class="text-xs text-muted-foreground">Updated recently</p>
                            </div>
                        </div>
                    </div>

                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Lessons This Week</h3>
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
                {{-- <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">Upcoming Lessons</h2>
                    <div class="space-y-4">
                        @foreach ($upcomingMeetings as $meeting)
                            @php
                                $hoursDiff = now()->diffInHours(\Carbon\Carbon::parse($meeting['start_time']), false);
                            @endphp
                            <div
                                class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow m-2">

                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span
                                                class="flex h-full w-full items-center justify-center rounded-full bg-muted">J</span></span>
                                        <div>
                                            <p class="font-semibold text-foreground">{{ $meeting['teacher_name'] }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $meeting['topic'] }} -
                                                {{ $meeting['duration'] }} min</p>
                                            <p class="text-sm text-warning font-medium flex items-center"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="mr-1 h-3.5 w-3.5">
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
                                        <a href="{{ $meeting['join_url'] }}" target="_blank">
                                            <button
                                                class="inline-flex items-center justify-center text-sm font-medium bg-warning text-warning-foreground hover:bg-warning/90 h-9 rounded-md px-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="h-4 w-4 mr-2">
                                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                                    <rect width="14" height="12" x="2" y="6" rx="2"
                                                        ry="2">
                                                    </rect>
                                                </svg>
                                                Join Lesson
                                            </button>
                                        </a>
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="inline-flex items-center justify-center text-sm font-medium border rounded-md px-3 h-9 bg-white hover:bg-gray-100">
                                                Options
                                                <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.292l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.65a.75.75 0 01-1.08 0l-4.25-4.65a.75.75 0 01.02-1.06z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <div
                                                class="absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                <div class="py-1">
                                                    <button type="button"
                                                        onclick="alert('{{ $hoursDiff < 24 ? 'If you cancel the lesson now, it will be paid to the tutor as it’s cancelled within the last 24 hours.' : 'Lesson cancelled successfully.' }}')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Cancel
                                                    </button>
                                                    <button type="button"
                                                        @if ($hoursDiff < 24) onclick="alert('Can’t reschedule within the last 24 hours')"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-400 cursor-not-allowed"
                                                                    disabled
                                                                    @else
                                                                    onclick="alert('Lesson rescheduled successfully.')"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @endif>
                                                        Reschedule
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-6"><button
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Show
                            More (1 more)</button></div>
                </div> --}}
                <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">Upcoming Lessons</h2>

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
                                                class="flex h-full w-full items-center justify-center rounded-full bg-muted">J</span>
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

                                        @if ($pivot && $pivot->has_joined)
                                            <a href="{{ $meeting['join_url'] }}" target="_blank">
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium bg-primary text-white hover:bg-primary/90 h-9 rounded-md px-3">
                                                    Rejoin Lesson
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{ route('student.zoom.join', $meeting['id']) }}">
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium bg-success text-white hover:bg-success/90 h-9 rounded-md px-3">
                                                    Join Lesson
                                                </button>
                                            </a>
                                        @endif

                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                id="lessonOptions{{ $index }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Options
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="lessonOptions{{ $index }}">
                                                <li>
                                                    <button type="button" class="dropdown-item"
                                                        onclick="alert('{{ $hoursDiff < 24 ? 'If you cancel the lesson now, it will be paid to the tutor because it is cancelled within the last 24 hours.' : 'Lesson cancelled successfully.' }}')">
                                                        Cancel
                                                    </button>
                                                </li>
                                                <li>
                                                    @if ($hoursDiff < 24)
                                                        <button class="dropdown-item disabled" type="button"
                                                            aria-disabled="true" tabindex="-1">
                                                            Cannot reschedule within the last 24 hours
                                                        </button>
                                                    @else
                                                        <button type="button" class="dropdown-item"
                                                            onclick="alert('Lesson rescheduled successfully.')">
                                                            Reschedule
                                                        </button>
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
