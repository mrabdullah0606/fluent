@extends('student.master.master')
@section('title', 'Customer Support - FluentAll')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-headset me-2"></i>{{ __('welcome.key_291') }}
                            </h4>
                            <small id="connection-status" class="text-light">{{ __('welcome.key_280') }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="sound-toggle" class="btn btn-outline-light btn-sm"
                                title="Toggle notification sounds">
                                <i id="sound-icon" class="bi bi-volume-up"></i>
                            </button>
                            <a href="{{ route('student.chats.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left"></i> {{ __('welcome.key_281') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>{{ __('welcome.key_300') }}</strong> {{ __('welcome.key_428') }}
                        </div>

                        <div id="chat-box"
                            style="height:400px; overflow-y:auto; border:1px solid #ddd; padding:15px; margin-bottom:15px; background-color:#f8f9fa;">
                            @foreach ($messages as $msg)
                                <div
                                    class="message mb-2 {{ $msg->sender->id === auth()->id() ? 'text-end' : 'text-start' }}">
                                    <div
                                        class="d-inline-block p-2 rounded {{ $msg->sender->id === auth()->id() ? 'bg-primary text-white' : 'bg-success text-white' }}">
                                        <strong>
                                            @if ($msg->sender->id === auth()->id())
                                                You
                                            @else
                                                <i class="bi bi-headset me-1"></i>Support
                                            @endif
                                        </strong>
                                        {{ $msg->message }}
                                        <br><small
                                            class="{{ $msg->sender->id === auth()->id() ? 'text-light' : 'text-light' }}">
                                            {{ $msg->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="chat-form" class="mt-3">
                            @csrf
                            <input type="hidden" id="receiver_id" value="{{ $admin->id }}">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control"
                                    placeholder="Type your question or message..." required maxlength="1000">
                                <button type="submit" class="btn btn-info" id="send-btn">{{ __('welcome.key_282') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <audio id="notification-sound" preload="auto">
        <source src="{{ asset('assets/website/sounds/notification-sound.wav') }}" type="audio/wav">
    </audio>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="messageToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">{{ __('welcome.key_302') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        // Use the same script logic as your existing chat, but with admin user
        Pusher.logToConsole = true;

        const currentUserId = {{ auth()->id() }};
        const receiverId = {{ $admin->id }};
        const user1 = Math.min(currentUserId, receiverId);
        const user2 = Math.max(currentUserId, receiverId);
        const channelName = `chat.${user1}.${user2}`;

        // Sound and Pusher setup (same as existing chat)
        let soundEnabled = localStorage.getItem('chatSoundEnabled') !== 'false';
        const notificationSound = document.getElementById('notification-sound');
        const soundToggle = document.getElementById('sound-toggle');
        const soundIcon = document.getElementById('sound-icon');

        function updateSoundToggle() {
            if (soundEnabled) {
                soundIcon.className = 'bi bi-volume-up';
                soundToggle.classList.remove('btn-outline-danger');
                soundToggle.classList.add('btn-outline-light');
            } else {
                soundIcon.className = 'bi bi-volume-mute';
                soundToggle.classList.remove('btn-outline-light');
                soundToggle.classList.add('btn-outline-danger');
            }
        }
        updateSoundToggle();

        soundToggle.addEventListener('click', function() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('chatSoundEnabled', soundEnabled);
            updateSoundToggle();
        });

        // Same Pusher setup as existing chat
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        const statusElement = document.getElementById('connection-status');
        const channel = pusher.subscribe(`private-${channelName}`);

        pusher.connection.bind('connected', function() {
            statusElement.textContent = 'Connected ✓';
            statusElement.className = 'text-success';
        });

        channel.bind('pusher:subscription_succeeded', function() {
            statusElement.textContent = 'Support chat ready ✓';
            statusElement.className = 'text-success';
        });

        channel.bind('message.sent', function(data) {
            if (data.sender_id !== currentUserId) {
                addMessageToChat('Support', data.message, data.created_at, false);
                showNotificationToast('Support', data.message);
                if (soundEnabled) {
                    notificationSound.play().catch(() => {});
                }
            }
        });

        function addMessageToChat(senderName, message, timestamp, isOwnMessage = false) {
            const chatBox = document.getElementById('chat-box');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message mb-2 ${isOwnMessage ? 'text-end' : 'text-start'}`;

            const time = new Date(timestamp).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const bgClass = isOwnMessage ? 'bg-primary' : 'bg-success';
            const displayName = isOwnMessage ? 'You' : '<i class="bi bi-headset me-1"></i>Support';

            messageDiv.innerHTML = `
                <div class="d-inline-block p-2 rounded ${bgClass} text-white">
                    <strong>${displayName}</strong>
                    ${message}
                    <br><small class="text-light">${time}</small>
                </div>
            `;

            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function showNotificationToast(senderName, message) {
            if (typeof bootstrap !== 'undefined') {
                const toastElement = document.getElementById('messageToast');
                const toastBody = document.getElementById('toastBody');
                if (toastElement && toastBody) {
                    toastBody.innerHTML = `<strong>${senderName}:</strong> ${message}`;
                    const toast = new bootstrap.Toast(toastElement, {
                        autohide: true,
                        delay: 5000
                    });
                    toast.show();
                }
            }
        }

        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = document.getElementById('message');
            const message = messageInput.value.trim();
            const sendBtn = document.getElementById('send-btn');
            const receiverId = document.getElementById('receiver_id').value;

            if (!message) return;

            sendBtn.disabled = true;
            sendBtn.textContent = 'Sending...';

            fetch('{{ route('student.chat.send.support') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addMessageToChat('You', message, data.data.created_at, true);
                        messageInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
        });

        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });
    </script>
@endsection
