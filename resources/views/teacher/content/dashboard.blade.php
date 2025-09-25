@extends('teacher.master.master')
@section('title', 'Teacher Dashboard - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-2">Welcome Back, {{ $teacher->name }}!</h1>
                    <p class="text-muted-foreground mb-8">Here's what's happening on your FluentAll dashboard today.</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Available Balance</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
                                </svg>
                            </div>
                            <div class="p-6 pt-0">
                                <div class="text-2xl font-bold">${{ $wallet->balance ?? 0 }}</div>
                                <p class="text-xs text-muted-foreground">+ ${{ $wallet->total_withdrawn ?? 0 }} total
                                    withdrawal</p>
                            </div>
                        </div>
                    </div>

                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Total Students</h3>
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
                                <div class="text-2xl font-bold">{{ $totalEnrollers ?? 0 }}</div>
                                <p class="text-xs text-muted-foreground">Updated Recently</p>
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

                <!-- Warning Notice -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-8 flex items-center"
                    style="opacity: 1; transform: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 text-yellow-600 mr-3">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" x2="12" y1="8" y2="12"></line>
                        <line x1="12" x2="12.01" y1="16" y2="16"></line>
                    </svg>
                    <p class="text-sm text-yellow-800">Don't forget to update your availability for next week in your
                        <a href="{{ route('teacher.calendar') }}"
                            class="inline-flex items-center justify-center rounded-md ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 underline-offset-4 hover:underline p-0 h-auto text-sm text-yellow-900 font-semibold">calendar</a>!
                    </p>
                </div>

                <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">Upcoming Lessons</h2>

                    <div class="space-y-4" id="lessonsContainer">
                        @foreach ($visibleMeetings as $index => $meeting)
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
                                            <p class="font-semibold text-foreground">{{ $meeting['student_name'] }}</p>
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
                                        <a href="{{ $meeting['join_url'] }}" target="_blank">
                                            <button
                                                class="inline-flex items-center justify-center text-sm font-medium bg-warning text-warning-foreground hover:bg-warning/90 h-9 rounded-md px-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="h-4 w-4 mr-2">
                                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                                    <rect width="14" height="12" x="2" y="6" rx="2"
                                                        ry="2"></rect>
                                                </svg>
                                                Join Lesson
                                            </button>
                                        </a>

                                        {{-- <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                type="button" id="lessonOptions{{ $index }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
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
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($visibleMeetings) > 4)
                        <div class="text-center mt-6">
                            <button id="loadMoreBtn"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                Show More ({{ count($visibleMeetings) - 4 }} left)
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
