
// Initialize Pusher
const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
    encrypted: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});

class ChatManager {
    constructor() {
        this.currentUserId = {{ auth()->id() }};
        this.chatUserId = {{ $user->id ?? 'null' }};
        this.messageInput = document.querySelector('#messageInput');
        this.sendButton = document.querySelector('#sendButton');
        this.messagesContainer = document.querySelector('#messagesContainer');
        this.channel = null;
        
        this.init();
    }

    init() {
        if (this.chatUserId) {
            this.subscribeToChannel();
            this.bindSendMessage();
            this.scrollToBottom();
        }
    }

    subscribeToChannel() {
        // Create channel name with sorted user IDs
        const user1 = Math.min(this.currentUserId, this.chatUserId);
        const user2 = Math.max(this.currentUserId, this.chatUserId);
        const channelName = `private-chat.${user1}.${user2}`;
        
        console.log('Subscribing to channel:', channelName);
        
        this.channel = pusher.subscribe(channelName);
        
        // Listen for message events
        this.channel.bind('message.sent', (data) => {
            console.log('Message received:', data);
            this.addMessageToChat(data);
        });

        // Handle subscription errors
        this.channel.bind('pusher:subscription_error', (error) => {
            console.error('Subscription error:', error);
        });

        this.channel.bind('pusher:subscription_succeeded', () => {
            console.log('Successfully subscribed to chat channel');
        });
    }

    bindSendMessage() {
        if (this.sendButton && this.messageInput) {
            this.sendButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.sendMessage();
            });

            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }
    }

    async sendMessage() {
        const message = this.messageInput.value.trim();
        
        if (!message) {
            return;
        }

        // Disable send button temporarily
        this.sendButton.disabled = true;
        this.sendButton.innerHTML = '<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        try {
            const response = await fetch('{{ route("teacher.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    receiver_id: this.chatUserId,
                    message: message
                })
            });

            const result = await response.json();

            if (result.success) {
                // Clear input
                this.messageInput.value = '';
                
                // Add message to chat immediately (optimistic update)
                this.addMessageToChat(result.data, true);
            } else {
                console.error('Send message error:', result.error);
                this.showError('Failed to send message: ' + result.error);
            }
        } catch (error) {
            console.error('Network error:', error);
            this.showError('Network error. Please try again.');
        } finally {
            // Re-enable send button
            this.sendButton.disabled = false;
            this.sendButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                    <path d="M22 2 11 13"></path>
                </svg>
            `;
        }
    }

    addMessageToChat(messageData, isOptimistic = false) {
        // Don't add duplicate messages from broadcast if we already added optimistically
        if (!isOptimistic && messageData.sender_id === this.currentUserId) {
            return;
        }

        const isOwnMessage = messageData.sender_id === this.currentUserId;
        const messageHtml = this.createMessageHtml(messageData, isOwnMessage);
        
        this.messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        this.scrollToBottom();
    }

    createMessageHtml(messageData, isOwnMessage) {
        const messageClass = isOwnMessage ? 'justify-end' : 'justify-start';
        const messageBg = isOwnMessage ? 'bg-primary text-primary-foreground' : 'bg-white border';
        const avatar = isOwnMessage ? '' : `
            <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                    ${messageData.sender_name.charAt(0).toUpperCase()}
                </span>
            </span>
        `;

        return `
            <div class="flex items-end gap-2 ${messageClass}">
                ${!isOwnMessage ? avatar : ''}
                <div class="max-w-md p-3 rounded-xl ${messageBg}" data-message-id="${messageData.id}">
                    <p>${this.escapeHtml(messageData.message)}</p>
                    <small class="text-xs opacity-70 block mt-1">${this.formatTime(messageData.created_at)}</small>
                </div>
                ${isOwnMessage ? avatar : ''}
            </div>
        `;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }

    showError(message) {
        // Create a simple error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);

        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
}

// Initialize chat when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (typeof pusher !== 'undefined') {
        new ChatManager();
    }
});