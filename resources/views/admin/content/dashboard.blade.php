@extends('admin.master.master')

@section('content')
    <!-- Sidebar -->
    <main class="main-content" id="main-content">
        <h3 class="fw-bold">Welcome Back, {{ auth()->user()->name }}!</h3>
        <p class="text-muted">Here's what's happening on your FluentAll dashboard today.</p>
        <div class="row g-4 my-4">

            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-credit-card"></i></div>
                    <p class="text-muted mb-1">Available Balance</p>
                    <h4 class="fw-bold mb-1">${{ $balance }}</h4>
                    <small class="text-muted">+ ${{ $totalWithdrawn }} since last withdrawal</small>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-people"></i></div>
                    <p class="text-muted mb-1">Total Teachers</p>
                    <h4 class="fw-bold mb-1">{{ $teachers->count() }}</h4>
                    <small class="text-muted">+{{ $newTeachersThisMonth }} new this month</small>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-people"></i></div>
                    <p class="text-muted mb-1">Total Students</p>
                    <h4 class="fw-bold mb-1">{{ $students->count() }}</h4>
                    <small class="text-muted">+{{ $newStudentsThisMonth }} new this month</small>
                </div>
            </div>

            {{-- <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-clock"></i></div>
                    <p class="text-muted mb-1">Lessons This Week</p>
                    <h4 class="fw-bold mb-1">12</h4>
                    <small class="text-muted">3 completed, 9 upcoming</small>
                </div>
            </div> --}}
        </div>
    </main>
@endsection
