@extends('admin.master.master')
@section('title', 'Add New User - FluentAll')
@section('content')
    <main class="main-content" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Add New Job</h3>
            <a href="{{ route('admin.careers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus-fill me-1"></i> Job Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.careers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <h5> <label for="job-title" class="form-label">Job Title</label></h5>
                        <input type="text" name="title" id="job-title"
                            class="form-control @error('title') is-invalid @enderror" placeholder="Software Engineer"
                            required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5> <label for="job-location" class="form-label">Location</label></h5>
                        <input type="text" name="location" id="job-location"
                            class="form-control @error('location') is-invalid @enderror" placeholder="Remote" required>
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between col-md-12 mb-3">
                        <div class="col-md-5 mb-3">
                            <h5><label for="job-salary" class="form-label">Salary</label></h5>
                            <input type="number" name="salary" id="job-salary"
                                class="form-control @error('salary') is-invalid @enderror" placeholder="Job Salary"
                                required>
                            @error('salary')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <h5><label for="job-type" class="form-label">Job Type</label></h5>
                            <select name="type" id="job-type" class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value="">Select Job Type</option>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="contract">Contract</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <h5><label for="job-status" class="form-label">Job Status</label></h5>
                        <select name="is_active" id="job-status"
                            class="form-control @error('is_active') is-invalid @enderror" required>
                            <option value="">Select Job Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('is_active')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <h5><label for="job-description" class="form-label">Description</label></h5>
                        <textarea name="description" id="job-description" class="form-control @error('description') is-invalid @enderror"
                            rows="4" placeholder="Job Description" required></textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Save Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
