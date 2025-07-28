@extends('website.master.master')
@section('title', 'Teacher Register - FluentAll')
@section('content')
    @push('styles')
        <style>
            .navbar-nav .nav-link:hover {
                color: #fdbd00 !important;
            }

            .login-box {
                max-width: 400px;
                margin: auto;
                border: 1px solid #ffc107;
                border-radius: 15px;
                box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
                padding: 30px;
                background-color: #fff;
            }

            .form-control:focus {
                border-color: #ffc107;
                box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
            }

            .input-group-text {
                background-color: #fff;
                border-right: 0;
            }

            .form-control {
                border-left: 0;
            }

            .login-btn {
                background-color: #e53935;
                color: white;
            }

            .login-btn:hover {
                background-color: #FFC107;
                color: white;
            }


            .form-icon {
                border-color: #ffc107;
            }

            @media (max-width: 356px) {
                .responsive-wrap {
                    white-space: normal !important;
                }
            }
        </style>
    @endpush
    <div class="container py-5">
        <div class="login-box">
            <h3 class="text-center fw-bold mb-3">Register as Teacher</h3>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teacher.register.submit') }}" method="POST">
                @csrf

                <input type="text" name="role" value="teacher" hidden>
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="Your Name" required>
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn login-btn shadow-sm border-0 px-4 py-2 fw-semibold">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
