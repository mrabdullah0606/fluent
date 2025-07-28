@extends('teacher.master.master')
@section('title', 'Teacher Dashboard - FluentAll')
@section('content')
    {{-- <div class="container-fluid px-3 px-md-4 px-lg-5 py-5">

        <h3 class="fw-bold">Welcome Back, Teacher!</h3>
        <p class="text-muted">Here's what's happening on your FluentAll dashboard today.</p>

        <div class="row g-4 my-4">
            <!-- Available Balance Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-credit-card"></i></div>
                    <p class="text-muted mb-1">Available Balance</p>
                    <h4 class="fw-bold mb-1">$2,435.50</h4>
                    <small class="text-muted">+ $120.00 since last withdrawal</small>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-people"></i></div>
                    <p class="text-muted mb-1">Total Students</p>
                    <h4 class="fw-bold mb-1">23</h4>
                    <small class="text-muted">+2 new this month</small>
                </div>
            </div>

            <!-- Lessons This Week Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-clock"></i></div>
                    <p class="text-muted mb-1">Lessons This Week</p>
                    <h4 class="fw-bold mb-1">12</h4>
                    <small class="text-muted">3 completed, 9 upcoming</small>
                </div>
            </div>
        </div>

        <!-- Alert -->
        <div class="alert alert-custom d-flex align-items-center mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2 text-warning"></i>
            <div>
                Don't forget to update your availability for next week in your <strong>calendar</strong>!
            </div>
        </div>


        <div class="container-fluid px-3 px-md-4 px-lg-5 py-5">
            <h4 class="fw-bold mb-4">Upcoming Lessons</h4>

            <!-- Lesson Card 1 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">J</div>
                    <div>
                        <div class="fw-bold">John Doe</div>
                        <div class="text-muted">English - 60 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Today at 14:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>

            <!-- Lesson Card 2 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">M</div>
                    <div>
                        <div class="fw-bold">Maria Garcia</div>
                        <div class="text-muted">Spanish - 30 min (Trial)</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Today at 16:30</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>

            <!-- Lesson Card 3 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">K</div>
                    <div>
                        <div class="fw-bold">Kenji Tanaka</div>
                        <div class="text-muted">Japanese - 60 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Tomorrow at 10:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>

            <!-- Lesson Card 4 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">F</div>
                    <div>
                        <div class="fw-bold">French Group A1</div>
                        <div class="text-muted">French - 90 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Tomorrow at 18:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>

            <!-- Show More Button -->
            <div class="text-center show-more-btn">
                <button class="btn btn-outline-secondary">Show More (1 more)</button>
            </div>
        </div>
    </div> --}}
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-2">Welcome Back, {{ $teacher->name }}!</h1>
                    <p class="text-muted-foreground mb-8">Here's what's happening on your FluentAll dashboard today.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Available Balance</h3><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
                                </svg>
                            </div>
                            <div class="p-6 pt-0">
                                <div class="text-2xl font-bold">$2,435.50</div>
                                <p class="text-xs text-muted-foreground">+ $120.00 since last withdrawal</p>
                            </div>
                        </div>
                    </div>
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:border-primary transition-colors">
                            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                                <h3 class="tracking-tight text-sm font-medium">Total Students</h3><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
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
                                <h3 class="tracking-tight text-sm font-medium">Lessons This Week</h3><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
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
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-8 flex items-center"
                    style="opacity: 1; transform: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-yellow-600 mr-3">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" x2="12" y1="8" y2="12"></line>
                        <line x1="12" x2="12.01" y1="16" y2="16"></line>
                    </svg>
                    <p class="text-sm text-yellow-800">Don't forget to update your availability for next week in your
                        <button
                            class="inline-flex items-center justify-center rounded-md ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 underline-offset-4 hover:underline p-0 h-auto text-sm text-yellow-900 font-semibold">calendar</button>!
                    </p>
                </div>
                <div style="opacity: 1; transform: none;">
                    <h2 class="text-2xl font-bold text-foreground mb-4">Upcoming Lessons</h2>
                    <div class="space-y-4">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3"><span
                                        class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span
                                            class="flex h-full w-full items-center justify-center rounded-full bg-muted">J</span></span>
                                    <div>
                                        <p class="font-semibold text-foreground">John Doe</p>
                                        <p class="text-sm text-muted-foreground">English - 60 min</p>
                                        <p class="text-sm text-primary font-medium flex items-center"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1 h-3.5 w-3.5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>Today at 14:00</p>
                                    </div>
                                </div>
                                <button
                                    class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                        <path d="m22 8-6 4 6 4V8Z"></path>
                                        <rect width="14" height="12" x="2" y="6" rx="2" ry="2">
                                        </rect>
                                    </svg>Join Lesson</button>
                            </div>
                        </div>
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3"><span
                                        class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span
                                            class="flex h-full w-full items-center justify-center rounded-full bg-muted">M</span></span>
                                    <div>
                                        <p class="font-semibold text-foreground">Maria Garcia</p>
                                        <p class="text-sm text-muted-foreground">Spanish - 30 min (Trial)</p>
                                        <p class="text-sm text-primary font-medium flex items-center"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1 h-3.5 w-3.5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>Today at 16:30</p>
                                    </div>
                                </div>
                                <button
                                    class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                        <path d="m22 8-6 4 6 4V8Z"></path>
                                        <rect width="14" height="12" x="2" y="6" rx="2" ry="2">
                                        </rect>
                                    </svg>Join Lesson</button>
                            </div>
                        </div>
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3"><span
                                        class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span
                                            class="flex h-full w-full items-center justify-center rounded-full bg-muted">K</span></span>
                                    <div>
                                        <p class="font-semibold text-foreground">Kenji Tanaka</p>
                                        <p class="text-sm text-muted-foreground">Japanese - 60 min</p>
                                        <p class="text-sm text-primary font-medium flex items-center"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1 h-3.5 w-3.5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>Tomorrow at 10:00</p>
                                    </div>
                                </div><button
                                    class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                        <path d="m22 8-6 4 6 4V8Z"></path>
                                        <rect width="14" height="12" x="2" y="6" rx="2" ry="2">
                                        </rect>
                                    </svg>Join Lesson</button>
                            </div>
                        </div>
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3"><span
                                        class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span
                                            class="flex h-full w-full items-center justify-center rounded-full bg-muted">F</span></span>
                                    <div>
                                        <p class="font-semibold text-foreground">French Group A1</p>
                                        <p class="text-sm text-muted-foreground">French - 90 min</p>
                                        <p class="text-sm text-primary font-medium flex items-center"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1 h-3.5 w-3.5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>Tomorrow at 18:00</p>
                                    </div>
                                </div><button
                                    class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 btn-red"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                        <path d="m22 8-6 4 6 4V8Z"></path>
                                        <rect width="14" height="12" x="2" y="6" rx="2" ry="2">
                                        </rect>
                                    </svg>Join Lesson</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-6"><button
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Show
                            More (1 more)</button></div>
                </div>
            </div>
        </div>
    </main>
@endsection
