@extends('website.master.master')
@section('title', 'Student Login - FluentAll')
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
        @if (session('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
        @endif

        <div class="login-box">
            <h3 class="text-center fw-bold">Log in As Student</h3>
            <form method="POST" action="{{ route('student.login') }}">
                @csrf
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="you@example.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group form-icon rounded border border-warning">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control border-0" id="password"
                            placeholder="••••••••" required>
                        <span class="input-group-text"><i class="bi bi-eye"></i></span>
                    </div>
                </div>

                <!-- Forgot Password -->
                <div class="mb-3 text-end">
                    <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
                </div>

                <!-- Log In Button -->
                <div class="d-grid">
                    <button type="submit" class="btn login-btn shadow-sm border-0 px-4 py-2 fw-semibold">Log
                        In</button>
                </div>
            </form>

        </div>
    </div>
@endsection
