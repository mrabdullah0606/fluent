@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('index') }}"><button class="back-btn"><i class="bi bi-arrow-left"></i>
                    Back</button></a>
        </div>

        <!-- Icon & Title -->
        <div class="text-center mb-4">
            <div class="icon-circle mb-2"><i class="bi bi-people"></i></div>
            <h2 class="fw-bold">My Tutor Messages</h2>
            <p class="text-muted">View and continue your conversations with tutors.</p>
        </div>

        <!-- Card Box -->
        <div class="bg-white p-4 card-box">
            <h6 class="fw-bold mb-3">Recent Tutor Conversations</h6>

            <!-- Search bar -->
            <input type="text" class="form-control search-bar mb-3" placeholder="Search conversations...">

            <!-- No conversation -->
            <p class="text-center no-convo">You have no active conversations with tutors yet.</p>
        </div>
    </div>
@endsection
