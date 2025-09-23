@extends('admin.master.master')
@section('title', 'Edit Applicant - FluentAll')

@section('content')
    <main class="main-content" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_606') }}</h3>
            <a href="{{ route('admin.applicants.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> {{ __('welcome.key_73') }}
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-1"></i> {{ __('welcome.key_607') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ __('welcome.key_608') }}</strong> {{ $applicant->career->title ?? 'N/A' }}</p>
                <p><strong>{{ __('welcome.key_609') }}</strong> {{ $applicant->career->location ?? 'N/A' }}</p>
                <p><strong>{{ __('welcome.key_610') }}</strong> {{ $applicant->career->salary ?? 'N/A' }}</p>
                <p><strong>{{ __('welcome.key_611') }}</strong> {{ ucfirst(str_replace('_', ' ', $applicant->career->type ?? 'N/A')) }}</p>
                <p><strong>{{ __('welcome.key_612') }}</strong> {{ $applicant->career->is_active ? 'Active' : 'Inactive' }}</p>
                <p><strong>{{ __('welcome.key_613') }}</strong> {{ $applicant->career->description ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-file-person-fill me-1"></i> {{ __('welcome.key_614') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.applicants.update', $applicant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_615') }}</label>
                            <input type="text" class="form-control" value="{{ $applicant->fullName }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_616') }}</label>
                            <input type="text" class="form-control" value="{{ $applicant->email }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_617') }}</label>
                            <input type="text" class="form-control" value="{{ $applicant->phone }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_618') }}</label>
                            <a href="{{ $applicant->linkedin }}" target="_blank"
                                class="form-control text-primary">{{ $applicant->linkedin }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_619') }}</label>
                            <a href="{{ $applicant->portfolio }}" target="_blank"
                                class="form-control text-primary">{{ $applicant->portfolio }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('welcome.key_620') }}</label>
                            <a href="{{ asset('storage/' . $applicant->cv_path) }}" target="_blank"
                                class="form-control text-primary">{{ __('welcome.key_621') }}</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('welcome.key_622') }}</label>
                        <textarea class="form-control" rows="3" disabled>{{ $applicant->coverLetter }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('welcome.key_623') }}</label>
                        <textarea class="form-control" rows="3" disabled>{{ $applicant->whyFit }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('welcome.key_624') }}</label>
                        <input type="text" class="form-control" value="{{ $applicant->expectedSalary }}" disabled>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('welcome.key_625') }}</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">{{ __('welcome.key_626') }}</option>
                            <option value="pending" {{ $applicant->status == 'pending' ? 'selected' : '' }}>{{ __('welcome.key_627') }}
                            </option>
                            <option value="accepted" {{ $applicant->status == 'accepted' ? 'selected' : '' }}>{{ __('welcome.key_628') }}
                            </option>
                            <option value="rejected" {{ $applicant->status == 'rejected' ? 'selected' : '' }}>{{ __('welcome.key_629') }}
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> {{ __('welcome.key_630') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
