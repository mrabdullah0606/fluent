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
                                <div class="text-2xl font-bold">23</div>
                                <p class="text-xs text-muted-foreground">+2 new this month</p>
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
                                <div class="text-2xl font-bold">12</div>
                                <p class="text-xs text-muted-foreground">3 completed, 9 upcoming</p>
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

                <!-- Upcoming Lessons -->
                <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">Upcoming Lessons</h2>

                    @if (count($visibleMeetings) === 0 && count($hiddenMeetings) === 0)
                        <div class="text-center py-8">
                            <p class="text-muted-foreground">No upcoming lessons scheduled.</p>
                        </div>
                    @else
                        <div class="space-y-4" id="lessons-container">
                            <!-- Visible Lessons (First 4) -->
                            @foreach ($visibleMeetings as $meeting)
                                <div
                                    class="lesson-item rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                                <span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                                                    {{ strtoupper(substr($meeting['student_name'] ?? ($meeting['group_name'] ?? 'N'), 0, 1)) }}
                                                </span>
                                            </span>
                                            <div>
                                                <p class="font-semibold text-foreground">
                                                    {{ $meeting['student_name'] ?? ($meeting['group_name'] ?? 'Unknown') }}
                                                </p>
                                                <p class="text-sm text-muted-foreground">
                                                    {{ ucfirst($meeting['topic']) }} - {{ $meeting['duration'] }} min
                                                </p>
                                                <p class="text-sm text-primary font-medium flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="mr-1 h-3.5 w-3.5">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($meeting['start_time'])->format('D, M j \\a\\t H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ $meeting['join_url'] }}" target="_blank">
                                            <button
                                                class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red">
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
                                    </div>
                                </div>
                            @endforeach

                            <!-- Hidden Lessons (Initially Hidden) -->
                            @foreach ($hiddenMeetings as $meeting)
                                <div class="lesson-item hidden-lesson rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow"
                                    style="display: none;">
                                    <div class="p-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                                <span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                                                    {{ strtoupper(substr($meeting['student_name'] ?? ($meeting['group_name'] ?? 'N'), 0, 1)) }}
                                                </span>
                                            </span>
                                            <div>
                                                <p class="font-semibold text-foreground">
                                                    {{ $meeting['student_name'] ?? ($meeting['group_name'] ?? 'Unknown') }}
                                                </p>
                                                <p class="text-sm text-muted-foreground">
                                                    {{ ucfirst($meeting['topic']) }} - {{ $meeting['duration'] }} min
                                                </p>
                                                <p class="text-sm text-primary font-medium flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="mr-1 h-3.5 w-3.5">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($meeting['start_time'])->format('D, M j \\a\\t H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ $meeting['join_url'] }}" target="_blank">
                                            <button
                                                class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red">
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
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Show More Button -->
                        @if (count($hiddenMeetings) > 0)
                            <div class="text-center mt-6">
                                <button id="show-more-btn" onclick="toggleLessons()"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                    <span id="button-text">Show More ({{ count($hiddenMeetings) }} more)</span>
                                    <svg id="expand-icon" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                    <svg id="collapse-icon" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"
                                        style="display: none;">
                                        <polyline points="18 15 12 9 6 15"></polyline>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        let isExpanded = false;

        function toggleLessons() {
            const hiddenLessons = document.querySelectorAll('.hidden-lesson');
            const buttonText = document.getElementById('button-text');
            const expandIcon = document.getElementById('expand-icon');
            const collapseIcon = document.getElementById('collapse-icon');

            if (!isExpanded) {
                // Show hidden lessons
                hiddenLessons.forEach(lesson => {
                    lesson.style.display = 'block';
                    // Add smooth animation
                    setTimeout(() => {
                        lesson.style.opacity = '1';
                        lesson.style.transform = 'translateY(0)';
                    }, 10);
                });

                buttonText.textContent = 'Show Less';
                expandIcon.style.display = 'none';
                collapseIcon.style.display = 'inline';
                isExpanded = true;
            } else {
                // Hide lessons
                hiddenLessons.forEach(lesson => {
                    lesson.style.display = 'none';
                    lesson.style.opacity = '0';
                    lesson.style.transform = 'translateY(-10px)';
                });

                buttonText.textContent = 'Show More ({{ count($hiddenMeetings) }} more)';
                expandIcon.style.display = 'inline';
                collapseIcon.style.display = 'none';
                isExpanded = false;
            }
        }

        // Add CSS for smooth transitions
        const style = document.createElement('style');
        style.textContent = `
    .hidden-lesson {
        transition: all 0.3s ease-in-out;
        opacity: 0;
        transform: translateY(-10px);
    }
    
    .lesson-item {
        transition: all 0.2s ease-in-out;
    }
`;
        document.head.appendChild(style);
    </script>

@endsection
