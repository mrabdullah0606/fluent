@extends('student.master.master')
@section('title', 'Chats - FluentAll')
@section('content')
    <div class="container py-4">
        <h4 class="mb-4">Your Chats</h4>
        @forelse ($users as $user)
            <a href="{{ route('student.chat.index', $user->id) }}" class="text-decoration-none text-dark">
                <div class="card mb-2 shadow-sm border-0 chat-user-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3"
                            style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $user->name }}</h6>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-secondary text-center">
                No chats found.
            </div>
        @endforelse
    </div>

    <style>
        .chat-user-card:hover {
            background-color: #f1f5f9;
            transform: scale(1.01);
            transition: 0.2s ease;
        }
    </style>
@endsection
