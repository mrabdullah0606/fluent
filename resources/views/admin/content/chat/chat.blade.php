@extends('admin.master.master')
@section('title', 'Chat with ' . $user->name . ' - Customer Support')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="bi bi-headset me-2"></i>
                                Customer Support - {{ $user->name }}
                            </h5>
                            <small id="connection-status" class="text-light opacity-75">Connecting...</small>
                            <div class="mt-1">
                                <small class="text-light">
                                    <i class="bi bi-person-badge me-1"></i>{{ ucfirst($user->role) }}
                                    <i class="bi bi-envelope ms-2 me-1"></i>{{ $user->email }}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="sound-toggle" class="btn btn-outline-light btn-sm"
                                title="Toggle notification sounds">
                                <i id="sound-icon" class="bi bi-volume-up"></i>
                            </button>
                            <a href="{{ route('admin.chat.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Back to Support
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chat-box"
                            style="height:450px; overflow-y:auto; border:1px solid #ddd; padding:15px; margin-bottom:15px; background-color:#f8f9fa; border-radius: 8px;">
                            @foreach ($messages as $msg)
                                <div
                                    class="message mb-3 {{ $msg->sender->id === auth()->id() ? 'text-end' : 'text-start' }}">
                                    <div class="d-inline-block p-3 rounded-3 {{ $msg->sender->id === auth()->id() ? 'bg-primary text-white admin-message' : 'bg-white border customer-message' }}"
                                        style="max-width: 75%; {{ $msg->sender->id === auth()->id() ? '' : 'box-shadow: 0 1px 3px rgba(0,0,0,0.1);' }}">
                                        <div class="message-header mb-1">
                                            @if ($msg->sender->id === auth()->id())
                                                <small class="text-light opacity-75">
                                                    <i class="bi bi-person-check me-1"></i>Support Agent
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>{{ $msg->sender->name }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="message-content">
                                            {{ $msg->message }}
                                        </div>
                                        <div class="message-time mt-2">
                                            <small
                                                class="{{ $msg->sender->id === auth()->id() ? 'text-light opacity-75' : 'text-muted' }}">
                                                <i class="bi bi-clock me-1"></i>{{ $msg->created_at->format('M j, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="chat-form" class="mt-3">
                            @csrf
                            <input type="hidden" id="receiver_id" value="{{ $user->id }}">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control form-control-lg"
                                    placeholder="Type your support response..." required maxlength="1000">
                                <button type="submit" class="btn btn-primary btn-lg" id="send-btn">
                                    <i class="bi bi-send me-1"></i>Send
                                </button>
                            </div>
                            <small class="text-muted mt-1">Press Enter to send • Max 1000 characters</small>
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
            <div class="toast-header bg-info text-white">
                <i class="bi bi-chat-left-text me-2"></i>
                <strong class="me-auto">New Customer Message</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        const currentUserId = {{ auth()->id() }};
        const receiverId = {{ $user->id }};
        const user1 = Math.min(currentUserId, receiverId);
        const user2 = Math.max(currentUserId, receiverId);
        const channelName = `chat.${user1}.${user2}`;

        console.log('Admin Chat - Current User ID:', currentUserId);
        console.log('Admin Chat - Customer ID:', receiverId);
        console.log('Admin Chat - Channel Name:', channelName);

        // Sound settings
        let soundEnabled = localStorage.getItem('adminChatSoundEnabled') !== 'false';
        const notificationSound = document.getElementById('notification-sound');
        const soundToggle = document.getElementById('sound-toggle');
        const soundIcon = document.getElementById('sound-icon');

        function updateSoundToggle() {
            if (soundEnabled) {
                soundIcon.className = 'bi bi-volume-up';
                soundToggle.classList.remove('btn-outline-warning');
                soundToggle.classList.add('btn-outline-light');
                soundToggle.title = 'Sound enabled - Click to disable';
            } else {
                soundIcon.className = 'bi bi-volume-mute';
                soundToggle.classList.remove('btn-outline-light');
                soundToggle.classList.add('btn-outline-warning');
                soundToggle.title = 'Sound disabled - Click to enable';
            }
        }
        updateSoundToggle();

        soundToggle.addEventListener('click', function() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('adminChatSoundEnabled', soundEnabled);
            updateSoundToggle();
            if (soundEnabled) {
                playNotificationSound();
            }
        });

        function playNotificationSound() {
            if (soundEnabled && notificationSound) {
                try {
                    notificationSound.currentTime = 0;
                    const playPromise = notificationSound.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function(error) {
                            console.log('Could not play notification sound:', error);
                            createSimpleBeep();
                        });
                    }
                } catch (error) {
                    console.log('Error playing notification sound:', error);
                    createSimpleBeep();
                }
            }
        }

        function createSimpleBeep() {
            if (!soundEnabled) return;

            try {
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.setValueAtTime(900, audioContext.currentTime);
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.4);
            } catch (error) {
                console.log('Could not create beep sound:', error);
            }
        }

        // Pusher setup
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

        pusher.connection.bind('connected', function() {
            statusElement.textContent = 'Connected ✓';
            statusElement.className = 'text-success';
            console.log('Admin chat connected to Pusher');
        });

        pusher.connection.bind('disconnected', function() {
            statusElement.textContent = 'Disconnected ✗';
            statusElement.className = 'text-warning';
            console.log('Admin chat disconnected from Pusher');
        });

        pusher.connection.bind('error', function(err) {
            statusElement.textContent = 'Error: ' + err.message;
            statusElement.className = 'text-danger';
            console.error('Admin chat Pusher connection error:', err);
        });

        const channel = pusher.subscribe(`private-${channelName}`);

        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Admin successfully subscribed to channel:', channelName);
            statusElement.textContent = 'Support chat ready ✓';
            statusElement.className = 'text-success';
        });

        channel.bind('pusher:subscription_error', function(err) {
            console.error('Admin subscription error:', err);
            statusElement.textContent = 'Failed to join chat';
            statusElement.className = 'text-danger';
        });

        channel.bind('message.sent', function(data) {
            console.log('Admin received message:', data);
            if (data.sender_id !== currentUserId) {
                addMessageToChat(data.sender_name, data.message, data.created_at, false);
                showNotificationToast(data.sender_name, data.message);
                showBrowserNotification(data.sender_name, data.message);
                playNotificationSound();
            }
        });

        function addMessageToChat(senderName, message, timestamp, isOwnMessage = false) {
            const chatBox = document.getElementById('chat-box');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message mb-3 ${isOwnMessage ? 'text-end' : 'text-start'}`;

            const time = new Date(timestamp).toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            const messageClass = isOwnMessage ? 'bg-primary text-white admin-message' : 'bg-white border customer-message';
            const headerClass = isOwnMessage ? 'text-light opacity-75' : 'text-muted';
            const timeClass = isOwnMessage ? 'text-light opacity-75' : 'text-muted';
            const icon = isOwnMessage ? 'bi-person-check' : 'bi-person';
            const senderLabel = isOwnMessage ? 'Support Agent' : senderName;

            messageDiv.innerHTML = `
                <div class="d-inline-block p-3 rounded-3 ${messageClass}" style="max-width: 75%; ${!isOwnMessage ? 'box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : ''}">
                    <div class="message-header mb-1">
                        <small class="${headerClass}">
                            <i class="bi ${icon} me-1"></i>${senderLabel}
                        </small>
                    </div>
                    <div class="message-content">${message}</div>
                    <div class="message-time mt-2">
                        <small class="${timeClass}">
                            <i class="bi bi-clock me-1"></i>${time}
                        </small>
                    </div>
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
                        delay: 6000
                    });

                    toast.show();
                }
            }
        }

        function showBrowserNotification(senderName, message) {
            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                new Notification(`Customer Support - ${senderName}`, {
                    body: message,
                    icon: '/favicon.ico',
                    tag: 'admin-chat-message'
                });
            }
        }

        // Send message
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const messageInput = document.getElementById('message');
            const message = messageInput.value.trim();
            const sendBtn = document.getElementById('send-btn');
            const receiverId = document.getElementById('receiver_id').value;

            if (!message) {
                return;
            }

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Sending...';

            fetch('{{ route('admin.chat.send') }}', {
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        addMessageToChat('Support Agent', message, data.data.created_at, true);
                        messageInput.value = '';
                    } else {
                        alert('Failed to send message: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error sending admin message:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="bi bi-send me-1"></i>Send';
                });
        });

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });

        // Enter key to send
        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>

    <style>
        .admin-message {
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.15);
        }

        .customer-message {
            border: 1px solid #e9ecef;
        }

        .message {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #chat-box {
            background-image: radial-gradient(circle at 1px 1px, rgba(0, 0, 0, .05) 1px, transparent 0);
            background-size: 20px 20px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }
    </style>
@endsection
