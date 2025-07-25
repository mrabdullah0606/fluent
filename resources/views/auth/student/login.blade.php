@extends('website.master.master')
@section('title', 'Student Login - FluentAll')
@section('content')
    <div class="container py-5">
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
