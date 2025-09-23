@extends('student.master.master')
@section('title', 'Add Reviews - FluentAll')

@section('content')
<div class="container my-5 bg-gray">
    <div class="card shadow-sm p-4 bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 mb-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>{{ __('welcome.key_458') }}</h3>
            <a href="{{route('student.public.profile')}}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> {{ __('welcome.key_484') }}
            </a>
        </div>

        {{-- Display success/failure messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        
        <form method="POST" action="">
            @csrf

            {{-- Full Name --}}
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('welcome.key_485') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" required placeholder="e.g., John D.">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Date --}}
            <div class="mb-3">
                <label for="date" class="form-label">{{ __('welcome.key_486') }}</label>
                <input type="date" id="date" name="date" value="{{ old('date', \Carbon\Carbon::today()->toDateString()) }}"
                    class="form-control @error('date') is-invalid @enderror" required>
                @error('date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Rating --}}
            <div class="mb-3">
                <label for="rating" class="form-label">{{ __('welcome.key_487') }}</label>
                <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                    <option value="">{{ __('welcome.key_488') }}</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
                @error('rating')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Review Text --}}
            <div class="mb-3">
                <label for="review" class="form-label">{{ __('welcome.key_490') }}</label>
                <textarea id="review" name="review" rows="4"
                    class="form-control @error('review') is-invalid @enderror" required
                    placeholder="Write something meaningful...">{{ old('review') }}</textarea>
                @error('review')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> {{ __('welcome.key_246') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
