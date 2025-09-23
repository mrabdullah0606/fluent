@extends('admin.master.master')
@section('title', 'Add New User - FluentAll')
@section('content')
    <main class="main-content" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_716') }}</h3>
            <a href="{{ route('admin.careers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> {{ __('welcome.key_73') }}
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus-fill me-1"></i> {{ __('welcome.key_607') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.careers.update', $career->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <h5> <label for="job-title" class="form-label">{{ __('welcome.key_599') }}</label></h5>
                        <input type="text" name="title" id="job-title" value="{{ old('title', $career->title) }}"
                            class="form-control @error('title') is-invalid @enderror" placeholder="Software Engineer"
                            required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5> <label for="job-location" class="form-label">{{ __('welcome.key_704') }}</label></h5>
                        <input type="text" name="location" id="job-location"
                            value="{{ old('location', $career->location) }}"
                            class="form-control @error('location') is-invalid @enderror" placeholder="Remote" required>
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between col-md-12 mb-3">
                        <div class="col-md-5 mb-3">
                            <h5><label for="job-salary" class="form-label">{{ __('welcome.key_705') }}</label></h5>
                            <input type="number" name="salary" id="job-salary"
                                value="{{ old('salary', $career->salary) }}"
                                class="form-control @error('salary') is-invalid @enderror" placeholder="Job Salary"
                                required>
                            @error('salary')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <h5><label for="job-type" class="form-label">{{ __('welcome.key_706') }}</label></h5>
                            <select name="type" id="job-type" class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value="">{{ __('welcome.key_707') }}</option>
                                <option value="full_time" {{ old('type', $career->type) == 'full_time' ? 'selected' : '' }}>
                                    {{ __('welcome.key_708') }}</option>
                                <option value="part_time" {{ old('type', $career->type) == 'part_time' ? 'selected' : '' }}>
                                    {{ __('welcome.key_709') }}</option>
                                <option value="contract" {{ old('type', $career->type) == 'contract' ? 'selected' : '' }}>
                                    {{ __('welcome.key_710') }}</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <h5><label for="job-status" class="form-label">{{ __('welcome.key_711') }}</label></h5>
                        <select name="is_active" id="job-status"
                            class="form-control @error('is_active') is-invalid @enderror" required>
                            <option value="">{{ __('welcome.key_712') }}</option>
                            <option value="1" {{ old('is_active', $career->is_active) == '1' ? 'selected' : '' }}>
                                {{ __('welcome.key_713') }}</option>
                            <option value="0" {{ old('is_active', $career->is_active) == '0' ? 'selected' : '' }}>
                                {{ __('welcome.key_714') }}</option>
                        </select>
                        @error('is_active')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <h5><label for="job-description" class="form-label">{{ __('welcome.key_385') }}</label></h5>
                        <textarea name="description" id="job-description" class="form-control @error('description') is-invalid @enderror"
                            rows="4" placeholder="Job Description" required>{{ old('description', $career->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> {{ __('welcome.key_715') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
