@extends('teacher.master.master')
@section('title', 'Booking Rules - FluentAll')
@section('content')
    <style>
        body {
            background-color: #F9FAFB;
        }

        .nav-link.active {
            background-color: #fff3cd !important;
            border-radius: 8px;
        }

        .nav-link:hover {
            background-color: #f5f5f5 !important;
            border-radius: 8px;
        }

        .btn-switch {
            border: 1px solid #fdbd00;
            color: #fdbd00;
            font-weight: 500;
        }

        .btn-switch:hover {
            background-color: #FFBF00;
        }

        .nav-text-orange {
            color: #ffae00;
            font-weight: 600;
        }

        .navbar-bottom-border {
            border-bottom: 1px solid #fdbd00;
        }

        .navbar-brand.nav-text-orange:hover {
            color: #ffae00;
            /* same as default */
            text-decoration: none;
            cursor: default;
        }

        .dropdown-toggle::after {
            display: none !important;
        }

        .dropdown-menu {
            min-width: 220px;
            margin-top: 50px;
            border: 1px solid #ffc107;
            /* Yellow border */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background-color: rgb(255, 242, 204) !important;
            /* Hover color */
        }

        .dropdown-item i {
            margin-right: 8px;
        }

        .sidebar {
            background-color: white;
            border-right: 1px solid #ddd;
            min-height: 100vh;
        }

        .setting-box {
            background-color: white;
            border: 1px solid #f0c040;
            border-radius: 6px;
            padding: 30px;
        }

        .setting-title {
            font-weight: 700;
        }

        .setting-description {
            color: #6c757d;
        }

        .setting-block {
            border-bottom: 1px solid #f1c40f;
            padding: 20px 0;
        }

        .setting-block:last-child {
            border-bottom: none;
        }

        .icon-yellow {
            color: #f1c40f;
            margin-right: 10px;
        }

        .save-btn {
            background-color: #dc3545;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
        }

        .save-btn i {
            margin-right: 8px;
        }

        .form-select {
            max-width: 150px;
        }
    </style>
    <main class="flex-grow">
        <div class="container my-4">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-gear-fill text-warning me-2"></i> Lesson Settings
            </h3>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="list-group">
                        <a href="{{ route('teacher.settings.index') }}" class="text-decoration-none">
                            <button class="btn list-group-item list-group-item-action border-0"
                                style="background-color: #FFF3CD color: black;">
                                Lesson Management
                            </button>
                        </a>

                        <a href="{{ route('teacher.bookings') }}"class="text-decoration-none"> <button
                                class="btn-2 list-group-item text-white list-group-item-action bg-danger">Booking
                                Rules</button></a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 col-12 p-4">
                    <form action="{{ route('teacher.bookings.update') }}" method="POST">
                        @csrf
                        <div class="setting-box">
                            <h5 class="setting-title">Booking Rules</h5>
                            <p class="setting-description">Define how and when students can book lessons with you.</p>

                            <!-- Rule 1: Minimum booking notice -->
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock icon-yellow"></i>
                                    <div>
                                        <strong>Minimum booking notice</strong><br>
                                        <small class="text-muted">How much advance notice you need for a new
                                            booking.</small>
                                    </div>
                                </div>
                                <select name="min_booking_notice" class="form-select mt-3 mt-md-0" required>
                                    @foreach (['12 hours', '24 hours', '48 hours'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $rule->min_booking_notice == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rule 2: Booking window -->
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 icon-yellow"></i>
                                    <div>
                                        <strong>Booking window</strong><br>
                                        <small class="text-muted">How far in the future students can book.</small>
                                    </div>
                                </div>
                                <select name="booking_window" class="form-select mt-3 mt-md-0" required>
                                    @foreach (['15 days', '30 days', '60 days'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $rule->booking_window == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rule 3: Break after lesson -->
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history icon-yellow"></i>
                                    <div>
                                        <strong>Automatic break after lesson</strong><br>
                                        <small class="text-muted">Set a buffer time after each lesson.</small>
                                    </div>
                                </div>
                                <select name="break_after_lesson" class="form-select mt-3 mt-md-0" required>
                                    @foreach (['none', '15 minutes', '30 minutes', '60 minutes'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $rule->break_after_lesson == $option ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rule 4: Accepting new students -->
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-plus icon-yellow"></i>
                                    <div>
                                        <strong>Accepting new students</strong><br>
                                        <small class="text-muted">Toggle if you are open to new student bookings.</small>
                                    </div>
                                </div>
                               <div class="form-check form-switch mt-3 mt-md-0">
    <!-- Hidden input to ensure false value is sent when unchecked -->
    <input type="hidden" name="accepting_new_students" value="0">
    <input class="form-check-input" type="checkbox" name="accepting_new_students" value="1">
</div>

                            </div>

                            <!-- Save Button -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn save-btn">
                                    <i class="bi bi-save"></i> Save All Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <button class="btn btn-warning rounded-circle shadow position-fixed bottom-0 end-0 m-4"
            style="width:60px; height:60px;">
            <i class="bi bi-chat-dots fs-4 text-dark"></i>
        </button>
    </main>
@endsection
