@extends('admin.master.master')
@section('title', 'Edit Applicant - FluentAll')

@section('content')
    <main class="main-content" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Edit Applicant</h3>
            <a href="{{ route('admin.applicants.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-1"></i> Job Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Title:</strong> {{ $applicant->career->title ?? 'N/A' }}</p>
                <p><strong>Location:</strong> {{ $applicant->career->location ?? 'N/A' }}</p>
                <p><strong>Salary:</strong> {{ $applicant->career->salary ?? 'N/A' }}</p>
                <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $applicant->career->type ?? 'N/A')) }}</p>
                <p><strong>Status:</strong> {{ $applicant->career->is_active ? 'Active' : 'Inactive' }}</p>
                <p><strong>Description:</strong> {{ $applicant->career->description ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-file-person-fill me-1"></i> Applicant Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.applicants.update', $applicant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name:</label>
                            <input type="text" class="form-control" value="{{ $applicant->fullName }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <input type="text" class="form-control" value="{{ $applicant->email }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone:</label>
                            <input type="text" class="form-control" value="{{ $applicant->phone }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">LinkedIn:</label>
                            <a href="{{ $applicant->linkedin }}" target="_blank"
                                class="form-control text-primary">{{ $applicant->linkedin }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Portfolio:</label>
                            <a href="{{ $applicant->portfolio }}" target="_blank"
                                class="form-control text-primary">{{ $applicant->portfolio }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CV:</label>
                            <a href="{{ asset('storage/' . $applicant->cv_path) }}" target="_blank"
                                class="form-control text-primary">View CV</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cover Letter:</label>
                        <textarea class="form-control" rows="3" disabled>{{ $applicant->coverLetter }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Why a Good Fit:</label>
                        <textarea class="form-control" rows="3" disabled>{{ $applicant->whyFit }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Expected Salary:</label>
                        <input type="text" class="form-control" value="{{ $applicant->expectedSalary }}" disabled>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Application Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="pending" {{ $applicant->status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="accepted" {{ $applicant->status == 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="rejected" {{ $applicant->status == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
