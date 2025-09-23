@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('index') }}"><button class="back-btn"><i class="bi bi-arrow-left"></i>
                    {{ __('welcome.key_73') }}</button></a>
        </div>

        <!-- Icon & Title -->
        <div class="text-center mb-4">
            <div class="icon-circle mb-2"><i class="bi bi-people"></i></div>
            <h2 class="fw-bold">{{ __('welcome.key_190') }}</h2>
            <p class="text-muted">{{ __('welcome.key_191') }}</p>
        </div>

        <!-- Card Box -->
        <div class="bg-white p-4 card-box">
            <h6 class="fw-bold mb-3">{{ __('welcome.key_192') }}</h6>

            <!-- Search bar -->
            <input type="text" class="form-control search-bar mb-3" placeholder="Search conversations...">

            <!-- No conversation -->
            <p class="text-center no-convo">{{ __('welcome.key_193') }}</p>
        </div>
    </div>
@endsection
