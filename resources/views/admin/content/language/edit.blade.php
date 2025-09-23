@extends('admin.master.master')
@section('title', 'Language Management - FluentAll')
@section('content')
    <main class="main-content" id="language-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_731') }}</h3>
            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> {{ __('welcome.key_73') }}
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-1"></i> {{ __('welcome.key_732') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.languages.update', $language->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="language-name" class="form-label">{{ __('welcome.key_733') }}</label>
                        <input type="text" name="name" id="language-name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $language->name) }}" placeholder="e.g., English, Urdu" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="language-symbol" class="form-label">{{ __('welcome.key_734') }}</label>
                        <input type="text" name="symbol" id="language-symbol"
                            class="form-control @error('symbol') is-invalid @enderror"
                            value="{{ old('symbol', $language->symbol) }}" placeholder="e.g., EN, UR" required>
                        @error('symbol')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> {{ __('welcome.key_735') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
