@extends('admin.master.master')
@section('title', 'Customer Support - Live Chat')
@section('content')

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">{{ __('welcome.key_720') }}</h4>
                        <small class="text-muted">{{ __('welcome.key_721') }}</small>
                    </div>
                    @if ($users->sum('unread_count') > 0)
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">{{ $users->sum('unread_count') }} unread</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                <i class="bi bi-check-all"></i> {{ __('welcome.key_290') }}
                            </button>
                        </div>
                    @endif
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-headset me-2"></i>
                        {{ __('welcome.key_722') }}
                    </div>
                    <div class="card-body p-0">
                        @forelse ($users as $user)
                            <a href="{{ route('admin.chat.show', $user->id) }}" class="text-decoration-none">
                                <div
                                    class="border-bottom chat-user-card {{ $user->unread_count > 0 ? 'unread-chat' : '' }} p-3">
                                    <div class="d-flex align-items-center">
                                        <!-- User avatar -->
                                        <div class="position-relative me-3">
                                            <div class="rounded-circle bg-{{ $user->unread_count > 0 ? 'danger' : 'primary' }} text-white d-flex justify-content-center align-items-center"
                                                style="width: 50px; height: 50px; font-weight: bold; font-size: 1.2rem;">
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

                                        <!-- User info -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="mb-0 {{ $user->unread_count > 0 ? 'fw-bold' : '' }}">
                                                        {{ $user->name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person-badge me-1"></i>
                                                        {{ ucfirst($user->role) }} • {{ $user->email }}
                                                    </small>
                                                </div>
                                                @if ($user->last_message)
                                                    <small class="text-muted">
                                                        {{ $user->last_message->created_at->diffForHumans() }}
                                                    </small>
                                                @endif
                                            </div>

                                            @if ($user->last_message)
                                                <div class="d-flex align-items-center">
                                                     @if ($user->last_message->sender_id === auth()->id())
                                                        <i class="bi bi-reply text-primary me-1"></i>
                                                        <small class="text-primary me-1">{{ __('welcome.key_294') }}</small>
                                                    @else
                                                        <i class="bi bi-chat-left-text text-info me-1"></i>
                                                    @endif
                                                    <p
                                                        class="mb-0 small {{ $user->unread_count > 0 ? 'fw-semibold text-dark' : 'text-muted' }}">
                                                        {{ Str::limit($user->last_message->message, 60) }}
                                                    </p>
                                                </div>
                                            @else
                                                <p class="mb-0 small text-muted fst-italic">{{ __('welcome.key_723') }}</p>
                                            @endif
                                        </div>

                                        <!-- Status indicators -->
                                        <div class="text-end">
                                           @if ($user->unread_count > 0)
                                                <div class="bg-danger rounded-circle mb-2"
                                                    style="width: 10px; height: 10px; margin-left: auto;"></div>
                                            @endif
                                            <i class="bi bi-chevron-right text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-chat-left-text-fill display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('welcome.key_724') }}</h5>
                                <p class="text-muted mb-0">{{ __('welcome.key_725') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chat-user-card {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .chat-user-card:hover {
            background-color: #f8f9fa !important;
            transform: translateX(2px);
            border-left-color: #0d6efd;
        }

        .unread-chat {
            border-left-color: #dc3545 !important;
            background-color: #fff5f5;
        }

        .unread-chat:hover {
            background-color: #ffe6e6 !important;
            border-left-color: #dc3545 !important;
        }

        .card-header {
            font-weight: 500;
        }
    </style>

    <script>
        function markAllAsRead() {
            if (!confirm('Mark all messages as read?')) {
                return;
            }

            fetch('{{ route('admin.chat.mark-all-read') }}', {
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
                    } else {
                        alert('Failed to mark messages as read');
                    }
                })
                .catch(error => {
                    console.error('Error marking messages as read:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        setInterval(() => {
            fetch('{{ route('admin.chat.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const currentUnread = {{ $users->sum('unread_count') }};
                        if (data.unread_count !== currentUnread) {
                            location.reload();
                        }
                    }
                })
                .catch(error => console.log('Auto-refresh error:', error));
        }, 30000);
    </script>

@endsection
