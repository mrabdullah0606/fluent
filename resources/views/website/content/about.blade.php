@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold section-heading">{{ __('welcome.key_23') }} <span>{{ __('welcome.key_24') }}</span></h2>
            <p class="text-muted">
                {{ __('welcome.key_25') }} <br>
                {{ __('welcome.key_26') }} <em>{{ __('welcome.key_27') }}</em>.
            </p>
        </div>

        <!-- Content Box -->
        <div class="about-box">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h5 class="mission-title">{{ __('welcome.key_28') }}</h5>
                    <p class="text-muted">
                        {{ __('welcome.key_29') }}
                    </p>

                    <h5 class="vision-title mt-4">{{ __('welcome.key_30') }}</h5>
                    <p class="text-muted">
                        {{ __('welcome.key_31') }}
                    </p>
                </div>

                <!-- Image -->
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('assets/website/images/hero-image.jpeg') }}" alt="Online Languages"
                        class="about-img">
                </div>
            </div>
        </div>
    </div>


    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-3">
                {{ __('welcome.key_32') }} <span class="text-gradient">{{ __('welcome.key_33') }}</span>
            </h2>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-person-check-fill"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_34') }}</h6>
                    <p class="text-small">{{ __('welcome.key_35') }}
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-bullseye"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_36') }}</h6>
                    <p class="text-small">{{ __('welcome.key_37') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-calendar-check-fill"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_38') }}</h6>
                    <p class="text-small">{{ __('welcome.key_39') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-shield-check"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_40') }}</h6>
                    <p class="text-small">{{ __('welcome.key_41') }}</p>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold fs-3">
                {{ __('welcome.key_42') }} <span class="text-gradient">{{ __('welcome.key_43') }}</span>
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-award-fill"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_44') }}</h6>
                    <p class="text-small">{{ __('welcome.key_45') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-globe-americas"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_46') }}</h6>
                    <p class="text-small">{{ __('welcome.key_47') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-people-fill"></i></div>
                    <h6 class="fw-bold">{{ __('welcome.key_48') }}</h6>
                    <p class="text-small">{{ __('welcome.key_49') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
