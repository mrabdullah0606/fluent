@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold section-title">{{ __('welcome.key_9') }} <span>{{ __('welcome.key_10') }}</span></h1>
            <p class="text-muted">{{ __('welcome.key_11') }}</p>
        </div>

        <div class="row justify-content-center">
            <!-- Contact Form -->
            <div class="col-md-6 mb-4">
                <div class="form-box">
                    <h5 class="fw-bold mb-4">{{ __('welcome.key_12') }}</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_13') }}</label>
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_14') }}</label>
                            <input type="email" class="form-control" placeholder="you@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_15') }}</label>
                            <input type="text" class="form-control" placeholder="How can we help?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('welcome.key_16') }}</label>
                            <textarea rows="4" class="form-control" placeholder="Your message..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send-fill me-2"></i>{{ __('welcome.key_17') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4">
                <div class="info-box mb-4">
                    <h6><i class="bi bi-envelope-fill me-2 text-warning"></i>{{ __('welcome.key_18') }}</h6>
                    <p class="mb-0 text-danger fw-semibold">support@fluentall.com</p>
                    <small class="text-muted">{{ __('welcome.key_19') }}</small>
                </div>

                <div class="info-box">
                    <h6><i class="bi bi-chat-dots-fill me-2 text-warning"></i>{{ __('welcome.key_20') }}</h6>
                    <button class="btn-start-chat mb-2">{{ __('welcome.key_21') }}</button><br />
                    <small class="text-muted">{{ __('welcome.key_22') }}</small>
                </div>
            </div>
        </div>
    </div>
@endsection
