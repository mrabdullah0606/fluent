@extends('student.master.master')
@section('title', 'Chats - FluentAll')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Chat with {{ $user->name }}</h4>
                    <small id="connection-status" class="text-muted">Connecting...</small>
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
                                <br><small class="text-muted">{{ $msg->created_at->format('H:i') }}</small>
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
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<!-- <script>
        Pusher.logToConsole = true;

        const currentUserId = {{ auth()->id() }};
        const receiverId = {{ $user->id }};
        const user1 = Math.min(currentUserId, receiverId);
        const user2 = Math.max(currentUserId, receiverId);
        const channelName = `chat.${user1}.${user2}`;

        console.log('Current User ID:', currentUserId);
        console.log('Receiver ID:', receiverId);
        console.log('Channel Name:', channelName);

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

        // Connection status handlers
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

        // IMPORTANT: Both files should subscribe to private channel
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

        // Listen for messages
        channel.bind('message.sent', function(data) {
            console.log('Message received:', data);
            // Only show message if it's not from current user
            if (data.sender_id !== currentUserId) {
                addMessageToChat(data.sender_name, data.message, data.created_at, false);
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
                    <br><small class="text-muted">${time}</small>
                </div>
            `;

            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Form submission - Use appropriate route for each file
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
            const sendUrl = '{{ route('student.chat.send') }}';

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
                        // Add message to chat immediately for sender
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

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        // Enter key to send message
        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });
    </script> -->
@endsection