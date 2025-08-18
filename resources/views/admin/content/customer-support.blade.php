@extends('admin.master.master')
@section('title', 'Live Chat Support')

@section('content')
<main>
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Live Chat Support</h1>
                        <p class="mb-0 text-muted">Manage customer conversations and support tickets</p>
                        <!-- Connection Status Indicator -->
                        <small class="text-muted">
                            Status: <span id="connection-status" class="badge badge-secondary">Connecting...</span>
                        </small>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="https://app.chatwoot.com/app/accounts/131300/dashboard"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>
                            Open Full Dashboard
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="loadConversations()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Refresh
                        </button>
                        <!-- Debug Panel Toggle -->
                        @if(config('app.debug'))
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleDebugPanel()">
                            <i class="fas fa-bug me-1"></i>
                            Debug
                        </button>
                        @endif
                        <a href="{{ route('admin.customer.support.webhook.config') }}"
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-cog me-1"></i>
                            Webhooks
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Panel (hidden by default) -->
        @if(config('app.debug'))
        <div class="row mb-4" id="debug-panel" style="display: none;">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="m-0"><i class="fas fa-bug mr-2"></i>Debug Panel</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6>System Status</h6>
                                <button class="btn btn-sm btn-info" onclick="debugSSE.getSystemStatus()">Check Status</button>
                                <button class="btn btn-sm btn-warning" onclick="debugSSE.clearCache()">Clear Cache</button>
                            </div>
                            <div class="col-md-3">
                                <h6>Connection</h6>
                                <button class="btn btn-sm btn-success" onclick="debugSSE.reconnect()">Reconnect SSE</button>
                                <button class="btn btn-sm btn-secondary" onclick="debugSSE.forceReload()">Reload Messages</button>
                            </div>
                            <div class="col-md-3">
                                <h6>Testing</h6>
                                <button class="btn btn-sm btn-primary" onclick="debugSSE.testMessage()">Test Message</button>
                                <button class="btn btn-sm btn-danger" onclick="debugSSE.clearProcessedIds()">Clear IDs</button>
                            </div>
                            <div class="col-md-3">
                                <h6>Logging</h6>
                                <button class="btn btn-sm btn-success" onclick="debugSSE.enableDebug()">Enable Debug</button>
                                <button class="btn btn-sm btn-danger" onclick="debugSSE.disableDebug()">Disable Debug</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Open Conversations</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="open-conversations">Loading...</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resolved Today</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="resolved-conversations">Loading...</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Contacts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-contacts">Loading...</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-conversations">Loading...</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Conversations List -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Conversations</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="conversationsDropdown"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="dropdown-header">Filter by Status:</div>
                                <a class="dropdown-item filter-conversations" href="#" data-status="open">
                                    <i class="fas fa-circle fa-sm fa-fw mr-2 text-success"></i> Open
                                </a>
                                <a class="dropdown-item filter-conversations" href="#" data-status="resolved">
                                    <i class="fas fa-check-circle fa-sm fa-fw mr-2 text-primary"></i> Resolved
                                </a>
                                <a class="dropdown-item filter-conversations" href="#" data-status="pending">
                                    <i class="fas fa-clock fa-sm fa-fw mr-2 text-warning"></i> Pending
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="conversations-loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Loading conversations...</p>
                        </div>
                        <div id="conversations-list" style="display: none;"></div>
                        <div id="conversations-error" class="text-center py-4" style="display: none;">
                            <div class="text-danger mb-3"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                            <h6 class="text-danger">Unable to Load Conversations</h6>
                            <p class="text-muted">Check your API configuration</p>
                            <button class="btn btn-primary btn-sm" onclick="loadConversations()">
                                <i class="fas fa-redo mr-1"></i> Retry
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages Panel -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Messages</h6>
                        <div id="typing-indicator" style="font-style: italic; font-size: 0.9rem; color: #666; display:none;">
                            <i class="fas fa-ellipsis-h fa-sm"></i> Typing...
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="messages-placeholder" class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Select a conversation to view messages</p>
                        </div>
                        <div id="messages-list" style="display: none; max-height: 400px; overflow-y: auto;"></div>

                        <!-- Quick Reply Form -->
                        <div id="quick-reply-form" style="display: none;" class="mt-3">
                            <form onsubmit="sendQuickReply(event)">
                                <div class="form-group">
                                    <textarea class="form-control" id="quick-message" rows="3" placeholder="Type your reply..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane mr-1"></i> Send Reply
                                    </button>
                                    <a href="#" class="btn btn-outline-secondary btn-sm" onclick="openFullConversation()" target="_blank">
                                        <i class="fas fa-external-link-alt mr-1"></i> Open Full Chat
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>

<!-- Enhanced CSS -->
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

.conversation-item {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    margin-bottom: 0.75rem;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s;
}
.conversation-item:hover {
    background-color: #f8f9fc;
    border-color: #4e73df;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.conversation-item.active {
    background-color: #4e73df;
    color: white;
    border-color: #4e73df;
}
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.message-item {
    margin-bottom: 1rem;
    padding: 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}
.message-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.message-incoming {
    background-color: #f8f9fc;
    border-left: 3px solid #4e73df;
}
.message-outgoing {
    background-color: #e2f3ff;
    border-left: 3px solid #1cc88a;
}
.message-time {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Connection status indicator */
#connection-status.connection-connected {
    background-color: #1cc88a;
}
#connection-status.connection-connecting {
    background-color: #f6c23e;
}
#connection-status.connection-error {
    background-color: #e74a3b;
}
#connection-status.connection-reconnecting {
    background-color: #36b9cc;
}

/* Enhanced animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.spinner-border {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Improved scrollbar for messages */
#messages-list::-webkit-scrollbar {
    width: 6px;
}
#messages-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}
#messages-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}
#messages-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<!-- Enhanced JavaScript -->
<script>
// Set CSRF token globally
window.Laravel = {
    csrfToken: '{{ csrf_token() }}'
};

// Add meta tag for CSRF token
const meta = document.createElement('meta');
meta.name = 'csrf-token';
meta.content = '{{ csrf_token() }}';
document.head.appendChild(meta);

// Debug panel toggle function
@if(config('app.debug'))
function toggleDebugPanel() {
    const panel = document.getElementById('debug-panel');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
    } else {
        panel.style.display = 'none';
    }
}
@endif

// Enhanced connection status display
function updateConnectionStatus(status) {
    const statusEl = document.getElementById('connection-status');
    if (!statusEl) return;
    
    statusEl.className = `badge connection-${status}`;
    
    const statusText = {
        'connected': 'Connected',
        'connecting': 'Connecting',
        'reconnecting': 'Reconnecting',
        'error': 'Error',
        'failed': 'Failed',
        'disconnected': 'Disconnected'
    };
    
    statusEl.textContent = statusText[status] || status;
}

// Stats display update function
function updateStatsDisplay(stats) {
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
            
            // Add animation on update
            element.style.transform = 'scale(1.1)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        }
    });
}

// Error notification system
function showNotification(message, type = 'info') {
    // You can integrate with your existing notification system here
    console.log(`[${type.toUpperCase()}] ${message}`);
    
    // Simple toast notification (you can replace with your preferred notification library)
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

// Performance monitoring
let performanceStart = Date.now();
window.addEventListener('load', () => {
    const loadTime = Date.now() - performanceStart;
    console.log(`Page loaded in ${loadTime}ms`);
});
</script>

<!-- Include the enhanced JavaScript -->
<script>
{!! file_get_contents(public_path('assets/admin/js/customer-support-enhanced.js')) !!}
</script>