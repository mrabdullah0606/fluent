@extends('teacher.master.master')
@section('title', 'Chats - FluentAll')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Chat with {{ $user->name }}</h4>
                            <small id="connection-status" class="text-muted">Connecting...</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Sound toggle button -->
                            <button type="button" id="sound-toggle" class="btn btn-outline-secondary btn-sm"
                                title="Toggle notification sounds">
                                <i id="sound-icon" class="bi bi-volume-up"></i>
                            </button>
                            <a href="{{ route('teacher.chats.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back to Chats
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chat-box"
                            style="height:400px; overflow-y:auto; border:1px solid #ddd; padding:15px; margin-bottom:15px; background-color:#f8f9fa;">
                            @foreach ($messages as $msg)
                                <div
                                    class="message mb-2 {{ $msg->sender->id === auth()->id() ? 'text-end' : 'text-start' }}">
                                    <div
                                        class="d-inline-block p-2 rounded {{ $msg->sender->id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                        <strong>{{ $msg->sender->id === auth()->id() ? 'You' : $msg->sender->name }}:</strong>
                                        {{ $msg->message }}
                                        <br><small
                                            class="{{ $msg->sender->id === auth()->id() ? 'text-light' : 'text-muted' }}">
                                            {{ $msg->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="chat-form" class="mt-3">
                            @csrf
                            <input type="hidden" id="receiver_id" value="{{ $user->id }}">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control" placeholder="Type a message..."
                                    required maxlength="1000">
                                <button type="submit" class="btn btn-primary" id="send-btn">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Audio element for notification sound -->
    <audio id="notification-sound" preload="auto">
        <!-- You can use multiple sources for better browser compatibility -->
        <source src="{{ asset('assets/website/sounds/notification-sound.wav') }}" type="audio/wav">
        <!-- Fallback notification sound (simple beep) -->
    </audio>
    <!-- Toast notification for new messages -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="messageToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">New Message</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                <!-- Message content will be inserted here -->
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

        console.log('Current User ID:', currentUserId);
        console.log('Receiver ID:', receiverId);
        console.log('Channel Name:', channelName);

        let soundEnabled = localStorage.getItem('chatSoundEnabled') !== 'false';
        const notificationSound = document.getElementById('notification-sound');
        const soundToggle = document.getElementById('sound-toggle');
        const soundIcon = document.getElementById('sound-icon');

        function updateSoundToggle() {
            if (soundEnabled) {
                soundIcon.className = 'bi bi-volume-up';
                soundToggle.classList.remove('btn-outline-danger');
                soundToggle.classList.add('btn-outline-secondary');
                soundToggle.title = 'Sound enabled - Click to disable';
            } else {
                soundIcon.className = 'bi bi-volume-mute';
                soundToggle.classList.remove('btn-outline-secondary');
                soundToggle.classList.add('btn-outline-danger');
                soundToggle.title = 'Sound disabled - Click to enable';
            }
        }
        updateSoundToggle();
        soundToggle.addEventListener('click', function() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('chatSoundEnabled', soundEnabled);
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

                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (error) {
                console.log('Could not create beep sound:', error);
            }
        }

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
            console.log('Connected to Pusher');
        });

        pusher.connection.bind('disconnected', function() {
            statusElement.textContent = 'Disconnected ✗';
            statusElement.className = 'text-danger';
            console.log('Disconnected from Pusher');
        });

        pusher.connection.bind('error', function(err) {
            statusElement.textContent = 'Error: ' + err.message;
            statusElement.className = 'text-danger';
            console.error('Pusher connection error:', err);
        });

        const channel = pusher.subscribe(`private-${channelName}`);

        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Successfully subscribed to channel:', channelName);
            statusElement.textContent = 'Chat ready ✓';
            statusElement.className = 'text-success';
        });

        channel.bind('pusher:subscription_error', function(err) {
            console.error('Subscription error:', err);
            statusElement.textContent = 'Failed to join chat';
            statusElement.className = 'text-danger';
        });

        channel.bind('message.sent', function(data) {
            console.log('Message received:', data);
            if (data.sender_id !== currentUserId) {
                addMessageToChat(data.sender_name, data.message, data.created_at, false);
                showNotificationToast(data.sender_name, data.message);
                showBrowserNotification(data.sender_name, data.message);
                updateNavbarNotificationCount();
                playNotificationSound();
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

            messageDiv.innerHTML = `
            <div class="d-inline-block p-2 rounded ${isOwnMessage ? 'bg-primary text-white' : 'bg-light'}">
                <strong>${isOwnMessage ? 'You' : senderName}:</strong>
                ${message}
                <br><small class="${isOwnMessage ? 'text-light' : 'text-muted'}">${time}</small>
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

        function updateNavbarNotificationCount() {
            const role = '{{ auth()->user()->role }}';
            const unreadCountRoute = role === 'teacher' ?
                '{{ route('teacher.chat.unread-count') }}' :
                '{{ route('student.chat.unread-count') }}';

            fetch(unreadCountRoute)
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    const messagesLink = document.querySelector('a[href*="chats"]');

                    if (data.unread_count > 0) {
                        if (badge) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                        } else if (messagesLink) {
                            const newBadge = document.createElement('span');
                            newBadge.className =
                                'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge';
                            newBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            messagesLink.appendChild(newBadge);
                        }
                    } else {
                        if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                });
        }
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
            sendBtn.textContent = 'Sending...';
            const role = '{{ auth()->user()->role }}';
            const sendUrl = role === 'teacher' ?
                '{{ route('teacher.chat.send') }}' :
                '{{ route('student.chat.send') }}';

            fetch(sendUrl, {
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
                        addMessageToChat('You', message, data.data.created_at, true);
                        messageInput.value = '';
                    } else {
                        alert('Failed to send message: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        function showBrowserNotification(senderName, message) {
            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                new Notification(`New message from ${senderName}`, {
                    body: message,
                    icon: '/favicon.ico',
                    tag: 'chat-message'
                });
            }
        }
    </script>
@endsection
