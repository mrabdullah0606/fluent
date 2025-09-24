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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background-color: rgb(255, 242, 204) !important;
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
                <i class="bi bi-gear-fill text-warning me-2"></i> {{ __('welcome.key_320') }}
            </h3>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="list-group">
                        <a href="{{ route('teacher.settings.index') }}" class="text-decoration-none">
                            <button class="btn list-group-item list-group-item-action border-0"
                                style="background-color: #FFF3CD color: black;">
                                {{ __('welcome.key_321') }}
                            </button>
                        </a>

                        <a href="{{ route('teacher.bookings') }}"class="text-decoration-none"> <button
                                class="btn-2 list-group-item text-white list-group-item-action bg-danger">{{ __('welcome.key_322') }}</button>
                        </a>
                    </div>
                </div>
                <div class="col-md-9 col-12 p-4">
                    <form action="{{ route('teacher.bookings.update') }}" method="POST">
                        @csrf
                        <div class="setting-box">
                            <h5 class="setting-title">{{ __('welcome.key_323') }}</h5>
                            <p class="setting-description">{{ __('welcome.key_324') }}</p>
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock icon-yellow"></i>
                                    <div>
                                        <strong>{{ __('welcome.key_325') }}</strong><br>
                                        <small class="text-muted">{{ __('welcome.key_326') }}</small>
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
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 icon-yellow"></i>
                                    <div>
                                        <strong>{{ __('welcome.key_327') }}</strong><br>
                                        <small class="text-muted">{{ __('welcome.key_328') }}</small>
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
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history icon-yellow"></i>
                                    <div>
                                        <strong>{{ __('welcome.key_329') }}</strong><br>
                                        <small class="text-muted">{{ __('welcome.key_330') }}</small>
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
                            <div class="setting-block d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-plus icon-yellow"></i>
                                    <div>
                                        <strong>{{ __('welcome.key_331') }}</strong><br>
                                        <small class="text-muted">{{ __('welcome.key_332') }}</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch mt-3 mt-md-0">
                                    <input class="form-check-input" type="checkbox" name="accepting_new_students"
                                        value="1" {{ $rule->accepting_new_students ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn save-btn">
                                    <i class="bi bi-save"></i> {{ __('welcome.key_333') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
