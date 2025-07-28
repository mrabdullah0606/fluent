@extends('website.master.master')
@section('title', 'Student Register - FluentAll')
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
            <h3 class="text-center fw-bold">Register As Student</h3>
            <p class="text-center text-muted">
                Don't have an account? <a href="#" class="text-warning text-decoration-none fw-semibold">Sign Up</a>
            </p>

            <form method="POST" action="{{ route('student.register') }}">
                @csrf
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name"
                            value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="you@example.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group form-icon rounded border border-warning">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control border-0" id="password" name="password"
                            placeholder="••••••••" required>
                        <span class="input-group-text"><i class="bi bi-eye"></i></span>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="••••••••" required>
                    </div>
                </div>
                <!-- Hidden Role -->
                <input type="hidden" name="role" value="student">
                <!-- Submit -->
                <div class="d-grid mt-4">
                    <button type="submit"
                        class="btn btn-warning shadow-sm border-0 px-4 py-2 fw-semibold">Register</button>
                </div>

                <!-- Already registered -->
                <div class="mt-3 text-center">
                    <a href="{{ route('student.login') }}" class="text-primary text-decoration-none">Already registered?</a>
                </div>
            </form>

        </div>
    </div>
@endsection
