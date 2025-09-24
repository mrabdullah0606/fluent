@extends('admin.master.master')
@section('title', 'User Management - FluentAll')
@section('content')
    <main class="main-content" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_743') }}</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> {{ __('welcome.key_73') }}
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-1"></i> {{ __('welcome.key_744') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="user-name" class="form-label">{{ __('welcome.key_13') }}</label>
                        <input type="text" name="name" id="user-name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="user-email" class="form-label">{{ __('welcome.key_739') }}</label>
                        <input type="email" name="email" id="user-email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="user-role" class="form-label">{{ __('welcome.key_737') }}</label>
                        <select name="role" id="user-role"
                            class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">{{ __('welcome.key_740') }}</option>
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>{{ __('welcome.key_642') }}</option>
                            <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>{{ __('welcome.key_521') }}</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="user-password" class="form-label">{{ __('welcome.key_745') }}</label>
                        <input type="password" name="password" id="user-password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('welcome.key_741') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> {{ __('welcome.key_746') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
