@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold section-title">Get In <span>Touch</span></h1>
            <p class="text-muted">We're here to help and answer any question you might have. We look forward to hearing
                from you!</p>
        </div>

        <div class="row justify-content-center">
            <!-- Contact Form -->
            <div class="col-md-6 mb-4">
                <div class="form-box">
                    <h5 class="fw-bold mb-4">Contact Form</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="you@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" placeholder="How can we help?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea rows="4" class="form-control" placeholder="Your message..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send-fill me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4">
                <div class="info-box mb-4">
                    <h6><i class="bi bi-envelope-fill me-2 text-warning"></i>Email Us</h6>
                    <p class="mb-0 text-danger fw-semibold">support@fluentall.com</p>
                    <small class="text-muted">We typically respond within 24 hours.</small>
                </div>

                <div class="info-box">
                    <h6><i class="bi bi-chat-dots-fill me-2 text-warning"></i>Live Chat</h6>
                    <button class="btn-start-chat mb-2">Start Chat</button><br />
                    <small class="text-muted">Usually available during business hours.</small>
                </div>
            </div>
        </div>
    </div>
@endsection
