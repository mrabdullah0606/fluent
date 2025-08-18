// Enhanced JavaScript with better debugging and message handling
// Replace your existing JavaScript section with this improved version

let selectedConversationId = null;
let conversationsData = [];
let eventSource = null;
let lastMessageId = 0;
let reconnectAttempts = 0;
let maxReconnectAttempts = 5;
let processedMessageIds = new Set();
let isMessageSending = false;
let heartbeatTimer = null;
let connectionStatus = 'disconnected';
let debugMode = true; // Set to false in production

// Enhanced debug function with levels
function debugLog(level, message, data = null) {
    if (!debugMode) return;
    
    const timestamp = new Date().toISOString();
    const logPrefix = `[${timestamp}] [${level.toUpperCase()}]`;
    
    switch(level) {
        case 'error':
            console.error(`${logPrefix} ${message}`, data || '');
            break;
        case 'warn':
            console.warn(`${logPrefix} ${message}`, data || '');
            break;
        case 'info':
            console.info(`${logPrefix} ${message}`, data || '');
            break;
        default:
            console.log(`${logPrefix} ${message}`, data || '');
    }
}

// Utility functions
function truncate(str, length) {
    if (!str) return '';
    return str.length > length ? str.substring(0, length) + '...' : str;
}

function formatTime(timestamp) {
    const date = new Date(typeof timestamp === 'string' ? timestamp : timestamp * 1000);
    return date.toLocaleString('en-US', {
        month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
}

function getStatusClass(status) {
    const classes = {
        'open': 'badge-success', 
        'resolved': 'badge-primary', 
        'pending': 'badge-warning', 
        'snoozed': 'badge-info'
    };
    return classes[status] || 'badge-secondary';
}

// Connection status indicator
function updateConnectionStatus(status) {
    connectionStatus = status;
    debugLog('info', 'Connection status changed', { status });
    
    // You can add a visual indicator here if needed
    // const indicator = document.getElementById('connection-indicator');
    // if (indicator) {
    //     indicator.className = `connection-${status}`;
    //     indicator.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    // }
}

// Enhanced stats loading
async function loadStats() {
    try {
        debugLog('debug', 'Loading stats');
        const response = await fetch('/admin/customer-support/stats', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const stats = data.data || {};
            debugLog('info', 'Stats loaded successfully', stats);
            
            // Update stats display if elements exist
            updateStatsDisplay(stats);
        } else {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
    } catch (e) {
        debugLog('error', 'Failed to load stats', e.message);
    }
}

function updateStatsDisplay(stats) {
    // Update stats elements if they exist
    const elements = {
        'open-conversations': stats.open_conversations,
        'resolved-conversations': stats.resolved_conversations,
        'total-contacts': stats.total_contacts,
        'pending-conversations': stats.pending_conversations
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    });
}

// Enhanced conversation loading
async function loadConversations(status = 'open') {
    debugLog('info', 'Loading conversations', { status });
    
    const loadingEl = document.getElementById('conversations-loading');
    const listEl = document.getElementById('conversations-list');
    const errorEl = document.getElementById('conversations-error');
    
    if (loadingEl) loadingEl.style.display = 'block';
    if (listEl) listEl.style.display = 'none';
    if (errorEl) errorEl.style.display = 'none';

    try {
        const response = await fetch(`/admin/customer-support/conversations?status=${status}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            conversationsData = data.data || [];
            displayConversations(conversationsData);
            
            if (loadingEl) loadingEl.style.display = 'none';
            if (listEl) listEl.style.display = 'block';
            
            debugLog('info', 'Conversations loaded successfully', { count: conversationsData.length });
        } else {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
    } catch (e) {
        debugLog('error', 'Failed to load conversations', e.message);
        
        if (loadingEl) loadingEl.style.display = 'none';
        if (errorEl) errorEl.style.display = 'block';
    }
}

function displayConversations(conversations) {
    const listEl = document.getElementById('conversations-list');
    if (!listEl) return;
    
    if (!conversations || conversations.length === 0) {
        listEl.innerHTML = `<div class="text-center py-4">
            <i class="fas fa-inbox fa-2x text-gray-300 mb-2"></i>
            <p class="text-muted">No conversations found</p>
        </div>`;
        return;
    }

    listEl.innerHTML = conversations.map(conv => {
        const senderName = conv.meta?.sender?.name || 'Unknown Customer';
        const senderEmail = conv.meta?.sender?.email || '';
        const lastMsg = (conv.messages?.length > 0) ? conv.messages[conv.messages.length - 1] : conv.last_non_activity_message;
        const lastMsgContent = lastMsg?.content || 'No messages yet';
        const assignee = conv.meta?.assignee?.name || 'Unassigned';
        const unreadCount = conv.unread_count || 0;
        const lastActivity = conv.last_activity_at ? formatTime(conv.last_activity_at * 1000) : '';

        return `<div class="conversation-item${selectedConversationId === conv.id ? ' active' : ''}" data-conversation-id="${conv.id}" onclick="selectConversation(event, ${conv.id})">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">${senderName}</h6>
                    ${senderEmail ? `<small class="text-muted">${senderEmail}</small><br>` : ''}
                    <p class="mb-1 text-muted small">${truncate(lastMsgContent, 50)}</p>
                    <small class="text-muted"><i class="fas fa-user fa-sm mr-1"></i>${assignee} • ${lastActivity}</small>
                </div>
                <div class="text-right">
                    <span class="badge status-badge ${getStatusClass(conv.status)}">${conv.status}</span>
                    ${unreadCount > 0 ? `<span class="badge badge-danger ml-1">${unreadCount}</span>` : ''}
                </div>
            </div>
        </div>`;
    }).join('');
}

// Enhanced conversation selection
async function selectConversation(event, conversationId) {
    event.preventDefault();
    
    debugLog('info', 'Selecting conversation', { 
        conversationId, 
        previous: selectedConversationId 
    });

    selectedConversationId = conversationId;
    lastMessageId = 0;
    processedMessageIds.clear();

    // Update UI
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    // Show form and hide placeholder
    const formEl = document.getElementById('quick-reply-form');
    const placeholderEl = document.getElementById('messages-placeholder');
    
    if (formEl) formEl.style.display = 'block';
    if (placeholderEl) placeholderEl.style.display = 'none';
    
    debugLog('debug', 'UI updated for conversation selection');

    await loadMessages(conversationId);
}

// Enhanced message loading
async function loadMessages(conversationId) {
    debugLog('info', 'Loading messages for conversation', conversationId);
    
    const messagesEl = document.getElementById('messages-list');
    if (!messagesEl) return;
    
    messagesEl.style.display = 'block';
    messagesEl.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm"></div><p class="mt-2">Loading messages...</p></div>';

    try {
        const response = await fetch(`/admin/customer-support/conversations/${conversationId}/messages`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const messages = data.data || [];
            
            debugLog('info', 'Messages loaded successfully', { 
                conversationId, 
                messageCount: messages.length,
                messages: messages.map(m => ({ 
                    id: m.id, 
                    type: m.message_type, 
                    content: m.content?.substring(0, 50) 
                }))
            });
            
            displayMessages(messages);
            
            // Update tracking variables
            messages.forEach(msg => {
                if (msg.id) {
                    processedMessageIds.add(msg.id);
                    if (msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                }
            });
            
            debugLog('debug', 'Message tracking updated', { 
                lastMessageId, 
                processedCount: processedMessageIds.size 
            });
            
        } else {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
    } catch (e) {
        debugLog('error', 'Failed to load messages', e.message);
        messagesEl.innerHTML = `<div class="text-center py-4">
            <div class="text-danger mb-2"><i class="fas fa-exclamation-triangle"></i></div>
            <p class="text-danger">Error loading messages</p>
            <button class="btn btn-sm btn-primary" onclick="loadMessages(${conversationId})">Retry</button>
        </div>`;
    }
}

function displayMessages(messages) {
    const messagesEl = document.getElementById('messages-list');
    if (!messagesEl) return;
    
    debugLog('debug', 'Displaying messages', { count: messages.length });
    
    if (!messages || messages.length === 0) {
        messagesEl.innerHTML = '<div class="text-center py-4"><p class="text-muted">No messages in this conversation</p></div>';
        return;
    }

    const messagesHTML = messages.map(msg => {
        const isIncoming = (msg.message_type === 0);
        const senderName = isIncoming ? (msg.sender?.name || 'Customer') : (msg.sender?.name || 'You');
        
        return `<div class="message-item ${isIncoming ? 'message-incoming' : 'message-outgoing'}" data-message-id="${msg.id}">
            <div class="mb-1">
                <strong>${senderName}</strong>
                <span class="message-time float-right">${formatTime(msg.created_at * 1000)}</span>
            </div>
            <div>${msg.content || msg.processed_message_content || '[No content]'}</div>
            ${msg.status ? `<small class="text-muted">Status: ${msg.status}</small>` : ''}
        </div>`;
    }).join('');

    messagesEl.innerHTML = messagesHTML;
    messagesEl.scrollTop = messagesEl.scrollHeight;
    
    debugLog('debug', 'Messages displayed successfully');
}

// Enhanced message appending
function appendMessage(message) {
    debugLog('debug', 'Attempting to append message', { 
        messageId: message?.id, 
        conversationId: message?.conversation_id || 'unknown',
        selectedConversation: selectedConversationId,
        messageType: message?.message_type,
        content: message?.content?.substring(0, 50)
    });
    
    if (!message || !message.id) {
        debugLog('warn', 'Skipping message without ID', message);
        return;
    }
    
    // Check if message already processed
    if (processedMessageIds.has(message.id)) {
        debugLog('debug', 'Skipping duplicate message', message.id);
        return;
    }
    
    // Add to processed set
    processedMessageIds.add(message.id);
    
    const messagesEl = document.getElementById('messages-list');
    if (!messagesEl) {
        debugLog('warn', 'Messages element not found');
        return;
    }

    // Check if messages area is visible
    const isVisible = messagesEl.style.display !== 'none';
    if (!isVisible) {
        debugLog('debug', 'Messages area not visible, showing it');
        messagesEl.style.display = 'block';
        const placeholderEl = document.getElementById('messages-placeholder');
        if (placeholderEl) placeholderEl.style.display = 'none';
    }

    // Normalize message type
    let normalizedType;
    if (typeof message.message_type === 'string') {
        normalizedType = (message.message_type === 'incoming' || message.message_type === '0') ? 0 : 1;
    } else {
        normalizedType = parseInt(message.message_type) || 0;
    }

    const isIncoming = normalizedType === 0;
    const senderName = isIncoming ? (message.sender?.name || 'Customer') : (message.sender?.name || 'You');

    // Handle timestamp formats
    let timestamp;
    if (typeof message.created_at === 'string') {
        timestamp = new Date(message.created_at);
    } else if (typeof message.created_at === 'number') {
        timestamp = new Date(message.created_at < 9999999999 ? message.created_at * 1000 : message.created_at);
    } else {
        timestamp = new Date();
    }

    const messageHTML = `
        <div class="message-item ${isIncoming ? 'message-incoming' : 'message-outgoing'}" data-message-id="${message.id}">
            <div class="mb-1">
                <strong>${senderName}</strong>
                <span class="message-time float-right">${formatTime(timestamp)}</span>
            </div>
            <div>${message.content || message.processed_message_content || '[No content]'}</div>
            ${message.status ? `<small class="text-muted">Status: ${message.status}</small>` : ''}
        </div>`;

    // Check if this is the first message (empty messages list)
    const existingMessages = messagesEl.querySelectorAll('.message-item');
    if (existingMessages.length === 0) {
        messagesEl.innerHTML = messageHTML;
    } else {
        messagesEl.insertAdjacentHTML('beforeend', messageHTML);
    }
    
    messagesEl.scrollTop = messagesEl.scrollHeight;

    if (message.id && message.id > lastMessageId) {
        lastMessageId = message.id;
    }
    
    debugLog('info', 'Message appended successfully', { 
        messageId: message.id, 
        newLastMessageId: lastMessageId,
        totalMessages: messagesEl.querySelectorAll('.message-item').length
    });
}

// Enhanced message sending
async function sendQuickReply(event) {
    event.preventDefault();
    
    debugLog('info', 'Sending quick reply', { 
        selectedConversationId, 
        isMessageSending 
    });
    
    if (!selectedConversationId || isMessageSending) {
        debugLog('warn', 'Cannot send message', { 
            selectedConversationId, 
            isMessageSending 
        });
        return;
    }

    const messageInput = document.getElementById('quick-message');
    if (!messageInput) return;
    
    const message = messageInput.value.trim();
    if (!message) {
        debugLog('warn', 'Empty message, not sending');
        return;
    }

    // Set sending flag
    isMessageSending = true;
    
    // Disable the form temporarily
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
    submitBtn.disabled = true;

    debugLog('debug', 'Sending message to API', { 
        conversationId: selectedConversationId, 
        message 
    });

    try {
        const response = await fetch(`/admin/customer-support/conversations/${selectedConversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ content: message })
        });
        
        if (response.ok) {
            const responseData = await response.json();
            messageInput.value = '';
            
            debugLog('info', 'Message sent successfully', responseData);
            
            // Message will be displayed via webhook/SSE
            
        } else {
            const errorData = await response.text();
            debugLog('error', 'Failed to send message', { 
                status: response.status, 
                error: errorData 
            });
            throw new Error(`Failed to send message: ${response.status}`);
        }
    } catch (e) {
        debugLog('error', 'Send message error', e.message);
        alert('Error sending message: ' + e.message);
    } finally {
        // Re-enable form
        isMessageSending = false;
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        debugLog('debug', 'Message sending completed');
    }
}

function openFullConversation() {
    if (!selectedConversationId) return;
    const url = `https://app.chatwoot.com/app/accounts/131300/conversations/${selectedConversationId}`;
    window.open(url, '_blank');
}

function filterConversations(status) {
    loadConversations(status);
}

// Enhanced typing event handling
function handleTypingEvent(data) {
    debugLog('debug', 'Handling typing event', data);
    
    if (!data.conversation_id || data.conversation_id !== selectedConversationId) {
        const typingEl = document.getElementById('typing-indicator');
        if (typingEl) typingEl.style.display = 'none';
        return;
    }
    
    const typingEl = document.getElementById('typing-indicator');
    if (!typingEl) return;
    
    if (data.typing_data?.is_typing) {
        typingEl.style.display = 'block';
    } else {
        typingEl.style.display = 'none';
    }
}

// Enhanced EventSource with better error handling
function initEventSource() {
    if (!window.EventSource) {
        debugLog('error', 'Browser does not support SSE');
        return;
    }

    // Close existing connection if any
    if (eventSource) {
        eventSource.close();
    }

    debugLog('info', 'Initializing SSE connection');
    updateConnectionStatus('connecting');
    
    eventSource = new EventSource('/admin/customer-support/stream-events');

    eventSource.onopen = () => {
        debugLog('info', 'SSE Connected successfully');
        updateConnectionStatus('connected');
        reconnectAttempts = 0;
        
        // Set up heartbeat monitoring
        if (heartbeatTimer) {
            clearTimeout(heartbeatTimer);
        }
        heartbeatTimer = setTimeout(() => {
            debugLog('warn', 'No heartbeat received, connection may be stale');
        }, 60000); // Expect heartbeat within 60 seconds
    };

    eventSource.onerror = (event) => {
        debugLog('error', 'SSE Connection error', event);
        updateConnectionStatus('error');
        
        if (heartbeatTimer) {
            clearTimeout(heartbeatTimer);
        }
        
        if (eventSource.readyState === EventSource.CLOSED) {
            debugLog('warn', 'SSE Connection closed, attempting to reconnect');
            updateConnectionStatus('reconnecting');
            attemptReconnect();
        }
    };

    eventSource.onmessage = (event) => {
        try {
            const payload = JSON.parse(event.data);
            debugLog('debug', 'SSE Received event', payload);
            
            if (!payload || !payload.type) return;

            switch (payload.type) {
                case 'message_created': {
                    const convId = payload.data.conversation_id;
                    const message = payload.data.message;

                    debugLog('info', 'SSE New message event', { 
                        convId, 
                        selectedConversationId, 
                        messageId: message?.id,
                        messageType: message?.message_type,
                        content: message?.content?.substring(0, 50)
                    });

                    if (message && message.id) {
                        if (convId == selectedConversationId) {
                            debugLog('debug', 'Message is for selected conversation, appending');
                            appendMessage(message);
                        } else {
                            debugLog('debug', 'Message is for different conversation', convId);
                        }
                    } else {
                        debugLog('warn', 'Invalid message data', message);
                    }

                    // Refresh conversations list to update last message
                    loadConversations();
                    loadStats();
                    break;
                }
                
                case 'new_conversation':
                case 'conversation_created':
                case 'conversation_status_changed':
                case 'conversation_updated':
                    debugLog('info', 'SSE Conversation event', payload.type);
                    loadConversations();
                    loadStats();
                    break;
                    
                case 'typing_update':
                    handleTypingEvent(payload.data);
                    break;
                    
                case 'connected':
                    debugLog('info', 'SSE Connection established');
                    updateConnectionStatus('connected');
                    break;
                    
                case 'heartbeat':
                    debugLog('debug', 'SSE Heartbeat received');
                    // Reset heartbeat timer
                    if (heartbeatTimer) {
                        clearTimeout(heartbeatTimer);
                    }
                    heartbeatTimer = setTimeout(() => {
                        debugLog('warn', 'No heartbeat received, connection may be stale');
                    }, 60000);
                    break;
                    
                case 'error':
                case 'fatal_error':
                    debugLog('error', 'SSE Server error', payload);
                    updateConnectionStatus('error');
                    break;
                    
                default:
                    debugLog('debug', 'SSE Unhandled event type', payload.type);
            }
        } catch (ex) {
            debugLog('error', 'SSE Invalid message data', ex.message);
        }
    };
}

function attemptReconnect() {
    if (reconnectAttempts >= maxReconnectAttempts) {
        debugLog('error', 'Max reconnection attempts reached');
        updateConnectionStatus('failed');
        return;
    }
    
    reconnectAttempts++;
    const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000);
    
    debugLog('info', `SSE Reconnecting in ${delay}ms (attempt ${reconnectAttempts})`);
    updateConnectionStatus('reconnecting');
    
    setTimeout(() => {
        initEventSource();
    }, delay);
}

// Cleanup function
function cleanup() {
    if (eventSource) {
        eventSource.close();
        debugLog('info', 'SSE Connection closed due to page unload');
    }
    
    if (heartbeatTimer) {
        clearTimeout(heartbeatTimer);
    }
}

// System diagnostics and debugging functions
window.debugSSE = {
    // Connection info
    getEventSource: () => eventSource,
    getConnectionStatus: () => connectionStatus,
    getSelectedConversation: () => selectedConversationId,
    getLastMessageId: () => lastMessageId,
    getProcessedIds: () => Array.from(processedMessageIds),
    getReconnectAttempts: () => reconnectAttempts,
    
    // Actions
    reconnect: () => {
        reconnectAttempts = 0;
        initEventSource();
    },
    
    clearProcessedIds: () => {
        processedMessageIds.clear();
        debugLog('info', 'Cleared processed message IDs');
    },
    
    forceReload: () => {
        if (selectedConversationId) {
            processedMessageIds.clear();
            loadMessages(selectedConversationId);
        }
    },
    
    testMessage: () => {
        if (!selectedConversationId) {
            alert('Please select a conversation first');
            return;
        }
        
        const testMsg = {
            id: Date.now(),
            content: 'Test message from debug function',
            message_type: 1,
            created_at: Date.now() / 1000,
            sender: { name: 'Test User' }
        };
        
        debugLog('info', 'Adding test message', testMsg);
        appendMessage(testMsg);
    },
    
    // Diagnostics
    getSystemStatus: async () => {
        try {
            const response = await fetch('/admin/customer-support/status', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                console.table(data.status);
                return data.status;
            }
        } catch (e) {
            debugLog('error', 'Failed to get system status', e.message);
        }
    },
    
    clearCache: async () => {
        try {
            const response = await fetch('/admin/customer-support/clear-cache', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                debugLog('info', 'Cache cleared', data);
                return data;
            }
        } catch (e) {
            debugLog('error', 'Failed to clear cache', e.message);
        }
    },
    
    // Logging controls
    enableDebug: () => {
        debugMode = true;
        debugLog('info', 'Debug mode enabled');
    },
    
    disableDebug: () => {
        debugMode = false;
        console.log('Debug mode disabled');
    },
    
    // Manual event simulation for testing
    simulateEvent: (type, data) => {
        debugLog('info', 'Simulating event', { type, data });
        const event = { data: JSON.stringify({ type, data }) };
        eventSource.onmessage(event);
    }
};

// Error handling for uncaught errors
window.addEventListener('error', (event) => {
    debugLog('error', 'Uncaught error', {
        message: event.message,
        filename: event.filename,
        lineno: event.lineno,
        colno: event.colno
    });
});

// Enhanced initialization
function initializeApp() {
    debugLog('info', 'Initializing customer support dashboard');
    
    // Check for required elements
    const requiredElements = [
        'conversations-list',
        'messages-list', 
        'quick-reply-form'
    ];
    
    const missingElements = requiredElements.filter(id => !document.getElementById(id));
    if (missingElements.length > 0) {
        debugLog('warn', 'Missing required elements', missingElements);
    }
    
    // Add CSRF token to meta if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = window.Laravel?.csrfToken || '';
        document.head.appendChild(meta);
    }
    
    // Initialize data loading
    loadStats();
    loadConversations();
    initEventSource();

    // Set up periodic refreshes
    setInterval(() => {
        loadStats();
    }, 60000); // Every minute
    
    // Set up filter event listeners
    document.querySelectorAll('.filter-conversations').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            filterConversations(this.dataset.status);
        });
    });
    
    debugLog('info', 'App initialization completed');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    initializeApp();
}

// Cleanup on page unload
window.addEventListener('beforeunload', cleanup);

// Expose global functions for inline event handlers
window.selectConversation = selectConversation;
window.sendQuickReply = sendQuickReply;
window.loadConversations = loadConversations;
window.openFullConversation = openFullConversation;
window.filterConversations = filterConversations;

// Performance monitoring
let performanceMetrics = {
    messageLoadTime: 0,
    conversationLoadTime: 0,
    sseLatency: 0
};

window.getPerformanceMetrics = () => performanceMetrics;

debugLog('info', 'Customer support JavaScript loaded successfully');