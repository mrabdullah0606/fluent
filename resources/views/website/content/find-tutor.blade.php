@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <div class="container text-center my-5">
        <h1 class="fw-bold">{{ __('welcome.key_1') }}</h1>
        <p class="text-muted">{{ __('welcome.key_2') }}</p>

        <div class="row justify-content-center mt-5 g-4">
            <!-- One-on-One Lessons -->
            <div class="col-md-5">
                <a href="{{ route('one.on.one.tutors') }}">
                    <div class="border p-4 border-warning lesson-card h-100">
                        <div class="icon text-warning"><i class="bi bi-person"></i></div>
                        <h4 class="fw-bold">{{ __('welcome.key_3') }}</h4>
                        <p class="text-muted">{{ __('welcome.key_4') }}</p>
                        <a href="{{ route('one.on.one.tutors') }}" class="btn btn-danger mt-3">{{ __('welcome.key_5') }}<i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </a>
            </div>

            <!-- Group Lessons -->
            {{-- <div class="col-md-5">
                <a href="{{ route('group.lesson') }}">
                    <div class="border p-4 border-warning lesson-card h-100">
                        <div class="icon text-danger"><i class="bi bi-people"></i></div>
                        <h4 class="fw-bold">{{ __('welcome.key_6') }}</h4>
                        <p class="text-muted">{{ __('welcome.key_7') }}</p>
                        <a href="{{ route('group.lesson') }}" class="btn btn-danger mt-3">{{ __('welcome.key_8') }}<i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </a>
            </div>{{ __('welcome.key_9') }}</div>
    </div>
@endsection
