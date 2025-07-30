@extends('admin.master.master')

@section('content')
    <!-- Sidebar -->
    <main class="main-content" id="main-content">
        <h3 class="fw-bold">Welcome Back, Admin!</h3>
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
            <!-- Show More Button -->
            <div class="text-center show-more-btn">
                <button class="btn btn-outline-secondary">Show More (1 more)</button>
            </div>
        </div>
    </main>
@endsection
