@extends('admin.master.master')
@section('title', 'Live Chat Support')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .users-list {
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            background-color: #ffffff;
        }

        .users-list h5 {
            font-weight: 600;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        .conversation-item {
            cursor: pointer;
            transition: background-color 0.2s ease;
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            position: relative;
        }

        .conversation-item:hover {
            background-color: #e9ecef;
        }

        .conversation-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            font-weight: 600;
        }

        .conversation-status {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-open {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-resolved {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .unread-indicator {
            width: 8px;
            height: 8px;
            background-color: #dc3545;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .chat-box {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: white;
            box-shadow: inset 0 0 15px rgb(0 0 0 / 0.05);
        }

        #chatWith {
            padding: 1rem 1rem 0.5rem 1rem;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
            color: #343a40;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .messages {
            padding: 1rem;
            height: calc(100vh - 130px);
            overflow-y: auto;
            background-color: #fefefe;
            scrollbar-width: thin;
            scrollbar-color: #ced4da transparent;
        }

        .messages::-webkit-scrollbar {
            width: 8px;
        }

        .messages::-webkit-scrollbar-thumb {
            background-color: #ced4da;
            border-radius: 4px;
        }

        .message {
            max-width: 75%;
            margin-bottom: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.05);
            word-wrap: break-word;
            font-size: 0.95rem;
            line-height: 1.4;
            position: relative;
        }

        .message.incoming {
            background-color: #e3f2fd;
            text-align: left;
            margin-right: auto;
            border-bottom-left-radius: 5px;
            color: #0d47a1;
        }

        .message.outgoing {
            background-color: #c8e6c9;
            text-align: right;
            margin-left: auto;
            border-bottom-right-radius: 5px;
            color: #1b5e20;
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 0.25rem;
        }

        .message-sender {
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            opacity: 0.8;
        }

        #chatForm {
            padding: 0.75rem 1rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        #messageInput {
            border-radius: 20px;
            font-size: 1rem;
            padding: 0.75rem 1rem;
            box-shadow: none;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease;
            resize: none;
        }

        #messageInput:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
            outline: none;
        }

        .btn-group {
            gap: 0.5rem;
        }

        .btn-send {
            border-radius: 20px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 100px;
        }

        .btn-send:hover:not(:disabled) {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }

        .btn-send:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .empty-state {
            text-align: center;
            color: #6c757d;
            padding: 3rem 1rem;
        }

        .empty-state i {
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        .contact-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .refresh-btn {
            border: none;
            background: none;
            color: #6c757d;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .refresh-btn:hover {
            color: #0d6efd;
            background-color: #f8f9fa;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 250px;
        }

        .typing-indicator {
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 20px;
            font-style: italic;
            color: #6c757d;
            animation: fadeInOut 1.5s infinite;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .users-list {
                height: 40vh;
                border-right: none;
                border-bottom: 1px solid #ddd;
            }

            .chat-box {
                height: 60vh;
            }

            .messages {
                height: calc(60vh - 130px);
            }

            .message {
                max-width: 85%;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row g-0">
            <!-- Conversations List -->
            <div class="col-md-4 users-list p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Active Conversations</h5>
                    <button class="refresh-btn" onclick="refreshConversations()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>

                <div id="conversationsList">
                    @if (isset($conversations) && count($conversations) > 0)
                        @foreach ($conversations as $conversation)
                            <div class="conversation-item {{ $loop->first ? 'active' : '' }}"
                                data-conversation-id="{{ $conversation['id'] }}"
                                onclick="loadConversation({{ $conversation['id'] }})">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">
                                            {{ $conversation['meta']['sender']['name'] ?? 'Anonymous User' }}
                                        </div>
                                        <div class="contact-info">
                                            @if (isset($conversation['meta']['sender']['email']))
                                                <div>{{ $conversation['meta']['sender']['email'] }}</div>
                                            @endif
                                            @if (isset($conversation['meta']['sender']['phone_number']))
                                                <div>{{ $conversation['meta']['sender']['phone_number'] }}</div>
                                            @endif
                                        </div>
                                        @if (isset($conversation['last_non_activity_message']['content']))
                                            <div class="text-truncate mt-1" style="max-width: 200px;">
                                                {{ Str::limit(strip_tags($conversation['last_non_activity_message']['content']), 50) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="conversation-status status-{{ $conversation['status'] }}">
                                            {{ $conversation['status'] }}
                                        </span>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ \Carbon\Carbon::parse($conversation['updated_at'])->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                @if ($conversation['unread_count'] > 0)
                                    <div class="unread-indicator"></div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox fa-2x"></i>
                            <p>No active conversations</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chat Box -->
            <div class="col-md-8 chat-box d-flex flex-column">
                <div id="chatWith">
                    <span>Select a conversation to start chatting</span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-success me-2" id="resolveBtn" onclick="resolveConversation()"
                            style="display: none;">
                            <i class="fas fa-check"></i> Resolve
                        </button>
                    </div>
                </div>

                <div class="messages" id="messages">
                    <div class="empty-state">
                        <i class="fas fa-comments fa-3x"></i>
                        <p>Select a conversation to view messages</p>
                    </div>
                </div>

                <form id="chatForm" class="d-flex align-items-end" style="display: none;">
                    <div class="flex-grow-1 me-2">
                        <textarea id="messageInput" class="form-control" placeholder="Type your reply..." rows="1"
                            style="resize: none; overflow: hidden;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-send" id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                    <input type="hidden" id="currentConversationId" />
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

    <script>
        let currentConversationId = null;
        let refreshInterval = null;
        let isLoading = false;

        // Auto-resize textarea
        const messageInput = document.getElementById('messageInput');
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Handle Enter key (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chatForm').dispatchEvent(new Event('submit'));
            }
        });

        function loadConversation(conversationId) {
            if (isLoading) return;

            // Update UI
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });

            const selectedItem = document.querySelector(`[data-conversation-id="${conversationId}"]`);
            if (selectedItem) {
                selectedItem.classList.add('active');
                // Remove unread indicator
                const unreadIndicator = selectedItem.querySelector('.unread-indicator');
                if (unreadIndicator) {
                    unreadIndicator.remove();
                }
            }

            currentConversationId = conversationId;
            document.getElementById('currentConversationId').value = conversationId;

            // Show form and resolve button
            document.getElementById('chatForm').style.display = 'flex';
            document.getElementById('resolveBtn').style.display = 'inline-block';

            // Show loading state
            const messagesEl = document.getElementById('messages');
            messagesEl.innerHTML =
                '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading messages...</div>';

            // Load messages from Chatwoot
            fetch(`/admin/customer-support/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.conversation && data.messages) {
                        updateChatHeader(data.conversation);
                        displayMessages(data.messages);
                    }
                })
                .catch(error => {
                    console.error('Error loading conversation:', error);
                    showToast('Error loading conversation', 'error');
                    messagesEl.innerHTML = '<div class="empty-state"><p>Error loading messages</p></div>';
                });
        }

        function updateChatHeader(conversation) {
            const chatWithEl = document.getElementById('chatWith');
            const contactName = conversation.meta?.sender?.name || 'Anonymous User';
            const contactEmail = conversation.meta?.sender?.email || '';
            const statusBadge =
                `<span class="conversation-status status-${conversation.status}">${conversation.status}</span>`;

            chatWithEl.querySelector('span').innerHTML = `
                Chat with ${contactName} ${statusBadge}
                ${contactEmail ? `<br><small class="text-muted">${contactEmail}</small>` : ''}
            `;
        }

        function displayMessages(messages) {
            const messagesEl = document.getElementById('messages');
            messagesEl.innerHTML = '';

            if (!messages || messages.length === 0) {
                messagesEl.innerHTML = '<div class="empty-state"><p>No messages yet</p></div>';
                return;
            }

            messages.forEach(message => {
                // Skip activity messages
                if (message.message_type === 'activity') return;

                const div = document.createElement('div');
                div.classList.add('message');

                // Determine message direction
                if (message.message_type === 'incoming') {
                    div.classList.add('incoming');
                } else {
                    div.classList.add('outgoing');
                }

                const senderName = message.sender?.name || (message.message_type === 'incoming' ? 'User' : 'Admin');
                const messageTime = new Date(message.created_at * 1000).toLocaleString();

                div.innerHTML = `
                    <div class="message-sender">${senderName}</div>
                    <div>${message.content || ''}</div>
                    <div class="message-time">${messageTime}</div>
                `;

                messagesEl.appendChild(div);
            });

            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function refreshConversations() {
            fetch('/admin/customer-support/conversations')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateConversationsList(data.conversations);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing conversations:', error);
                });
        }

        function updateConversationsList(conversations) {
            const listEl = document.getElementById('conversationsList');

            if (!conversations || conversations.length === 0) {
                listEl.innerHTML =
                    '<div class="empty-state"><i class="fas fa-inbox fa-2x"></i><p>No active conversations</p></div>';
                return;
            }

            listEl.innerHTML = '';
            conversations.forEach((conversation, index) => {
                const div = document.createElement('div');
                div.className = `conversation-item ${index === 0 && !currentConversationId ? 'active' : ''}`;
                div.setAttribute('data-conversation-id', conversation.id);
                div.onclick = () => loadConversation(conversation.id);

                const contactName = conversation.meta?.sender?.name || 'Anonymous User';
                const contactEmail = conversation.meta?.sender?.email || '';
                const lastMessage = conversation.last_non_activity_message?.content || '';
                const updatedAt = new Date(conversation.updated_at).toLocaleString();

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-bold">${contactName}</div>
                            ${contactEmail ? `<div class="contact-info">${contactEmail}</div>` : ''}
                            ${lastMessage ? `<div class="text-truncate mt-1" style="max-width: 200px;">${lastMessage.substring(0, 50)}${lastMessage.length > 50 ? '...' : ''}</div>` : ''}
                        </div>
                        <div class="text-end">
                            <span class="conversation-status status-${conversation.status}">${conversation.status}</span>
                            <div class="text-muted" style="font-size: 0.7rem;">${new Date(conversation.updated_at).toLocaleDateString()}</div>
                        </div>
                    </div>
                    ${conversation.unread_count > 0 ? '<div class="unread-indicator"></div>' : ''}
                `;

                listEl.appendChild(div);
            });
        }

        // Handle form submission
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const messageText = messageInput.value.trim();
            if (!messageText || !currentConversationId) return;

            const sendBtn = document.getElementById('sendMessageBtn');
            const originalContent = sendBtn.innerHTML;

            // Show loading state
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('/admin/customer-support/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        conversation_id: currentConversationId,
                        message: messageText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        messageInput.style.height = 'auto';

                        // Reload messages after a short delay to show the sent message
                        setTimeout(() => {
                            loadConversationMessages(currentConversationId);
                        }, 500);

                        showToast('Message sent successfully', 'success');
                    } else {
                        showToast(data.message || 'Failed to send message', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    showToast('Error sending message', 'error');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = originalContent;
                    messageInput.focus();
                });
        });

        function loadConversationMessages(conversationId) {
            fetch(`/admin/customer-support/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages) {
                        displayMessages(data.messages);
                    }
                });
        }

        function resolveConversation() {
            if (!currentConversationId) return;

            if (!confirm('Are you sure you want to mark this conversation as resolved?')) return;

            fetch(`/admin/customer-support/resolve/${currentConversationId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Conversation resolved successfully', 'success');

                        // Remove from list or update status
                        const conversationItem = document.querySelector(
                            `[data-conversation-id="${currentConversationId}"]`);
                        if (conversationItem) {
                            conversationItem.remove();
                        }

                        // Reset chat area
                        resetChatArea();

                        // Refresh conversations list
                        setTimeout(refreshConversations, 1000);
                    } else {
                        showToast(data.message || 'Failed to resolve conversation', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error resolving conversation:', error);
                    showToast('Error resolving conversation', 'error');
                });
        }

        function resetChatArea() {
            currentConversationId = null;
            document.getElementById('chatForm').style.display = 'none';
            document.getElementById('resolveBtn').style.display = 'none';
            document.getElementById('chatWith').querySelector('span').textContent =
                'Select a conversation to start chatting';
            document.getElementById('messages').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-comments fa-3x"></i>
                    <p>Select a conversation to view messages</p>
                </div>
            `;
        }

        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');

            const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';

            toast.className = `toast show align-items-center text-white ${bgClass} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // Auto-refresh conversations every 30 seconds
        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                refreshConversations();
            }, 30000);
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        // Real-time updates via webhook notifications (if you implement WebSocket/Pusher)
        function setupRealTimeUpdates() {
            // This would connect to your broadcasting service
            // Example with Pusher:
            /*
            const pusher = new Pusher('your-pusher-key', {
                cluster: 'your-cluster'
            });
            
            const channel = pusher.subscribe('chatwoot-admin');
            channel.bind('new-message', function(data) {
                handleRealTimeMessage(data);
            });
            */
        }

        function handleRealTimeMessage(data) {
            if (data.conversation_id == currentConversationId) {
                // Reload current conversation messages
                loadConversationMessages(currentConversationId);
            } else {
                // Update conversation list and show notification
                refreshConversations();
                showToast(`New message from conversation #${data.conversation_id}`, 'info');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Load first conversation if exists
            const firstConversation = document.querySelector('.conversation-item');
            if (firstConversation) {
                const conversationId = firstConversation.getAttribute('data-conversation-id');
                loadConversation(conversationId);
            }

            // Start auto-refresh
            startAutoRefresh();

            // Setup real-time updates
            setupRealTimeUpdates();
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });

        // Focus management
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && currentConversationId) {
                // Refresh current conversation when tab becomes visible
                loadConversationMessages(currentConversationId);
            }
        });
    </script>

    @push('scripts')
        <!-- Add Pusher or your preferred real-time service -->
        {{-- <script src="https://js.pusher.com/7.2/pusher.min.js"></script> --}}
    @endpush
@endsection
