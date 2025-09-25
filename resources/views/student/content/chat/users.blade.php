@extends('student.master.master')
@section('title', 'Chats - FluentAll')
@section('content')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Your Chats</h4>
            @if ($users->sum('unread_count') > 0)
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                    Mark All as Read
                </button>
            @endif
        </div>

        <!-- Customer Support Card (Dynamic) -->
        <a href="{{ url('student/support') }}" class="text-decoration-none text-dark">
            <div class="card mb-2 shadow-sm border-0 chat-user-card {{ $supportUnreadCount > 0 ? 'unread-chat' : '' }}">
                <div class="card-body d-flex align-items-center">
                    <div class="position-relative me-3">
                        <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center"
                            style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem;">
                            <i class="bi bi-headset"></i>
                        </div>
                        @if ($supportUnreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.7rem;">
                                {{ $supportUnreadCount > 99 ? '99+' : $supportUnreadCount }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1 {{ $supportUnreadCount > 0 ? 'fw-bold' : '' }}">Customer Support</h6>
                            @if ($supportLastMessage)
                                <small class="text-muted">
                                    {{ $supportLastMessage->created_at->diffForHumans() }}
                                </small>
                            @else
                                <small class="text-muted">Available</small>
                            @endif
                        </div>

                        @if ($supportLastMessage)
                            <p class="mb-0 text-muted small {{ $supportUnreadCount > 0 ? 'fw-semibold text-dark' : '' }}">
                                @if ($supportLastMessage->sender_id === auth()->id())
                                    <span class="text-primary">You:</span>
                                @else
                                    <span class="text-success">Support:</span>
                                @endif
                                {{ Str::limit($supportLastMessage->message, 50) }}
                            </p>
                        @else
                            <p class="mb-0 text-muted small">
                                Need help? Contact our support team
                            </p>
                        @endif
                    </div>

                    @if ($supportUnreadCount > 0)
                        <div class="ms-2">
                            <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                        </div>
                    @endif
                </div>
            </div>
        </a>

        @forelse ($users as $user)
            <a href="{{ route('student.chat.index', $user->id) }}" class="text-decoration-none text-dark">
                <div class="card mb-2 shadow-sm border-0 chat-user-card {{ $user->unread_count > 0 ? 'unread-chat' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <!-- User avatar or initials -->
                        <div class="position-relative me-3">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @if ($user->unread_count > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.7rem;">
                                    {{ $user->unread_count > 99 ? '99+' : $user->unread_count }}
                                </span>
                            @endif
                        </div>

                        <!-- Name and preview -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1 {{ $user->unread_count > 0 ? 'fw-bold' : '' }}">{{ $user->name }}</h6>
                                @if ($user->last_message)
                                    <small class="text-muted">
                                        {{ $user->last_message->created_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>

                            @if ($user->last_message)
                                <p
                                    class="mb-0 text-muted small {{ $user->unread_count > 0 ? 'fw-semibold text-dark' : '' }}">
                                    @if ($user->last_message->sender_id === auth()->id())
                                        <span class="text-primary">You:</span>
                                    @endif
                                    {{ Str::limit($user->last_message->message, 50) }}
                                </p>
                            @endif
                        </div>

                        <!-- Unread indicator dot -->
                        @if ($user->unread_count > 0)
                            <div class="ms-2">
                                <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-secondary text-center">
                <i class="bi bi-chat-left-text-fill fs-1 text-muted mb-3 d-block"></i>
                <h6>No chats found</h6>
                <p class="mb-0 text-muted">Start a conversation to see your chats here.</p>
            </div>
        @endforelse
    </div>

    <style>
        .chat-user-card {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .chat-user-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .unread-chat {
            border-left-color: #0d6efd !important;
            background-color: #f8f9ff;
        }

        .unread-chat:hover {
            background-color: #e7f1ff;
        }
    </style>

    <script>
        function markAllAsRead() {
            fetch('{{ route('student.chat.mark-all-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error marking messages as read:', error);
                });
        }

        // Auto-refresh unread counts every 30 seconds
        setInterval(() => {
            // You can add a subtle refresh here if needed
            // Or use WebSockets for real-time updates
        }, 30000);
    </script>

@endsection
