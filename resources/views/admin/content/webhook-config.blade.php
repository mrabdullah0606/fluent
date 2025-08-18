{{-- resources/views/admin/content/webhook-config.blade.php --}}
@extends('admin.master.master')
@section('title', 'Webhook Configuration')

@section('content')
<main class="main-content" id="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Chatwoot Webhook Configuration</h3>
                        <p class="text-muted mb-0">Configure real-time webhooks for your Laravel dashboard</p>
                    </div>
                    <div class="card-body">
                        <!-- Webhook URL Section -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5>Your Webhook URL</h5>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="webhook-url" 
                                           value="{{ route('admin.customer.support.webhook') }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyWebhookUrl()">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Use this URL in your Chatwoot webhook settings</small>
                            </div>
                            <div class="col-md-4">
                                <h5>Webhook Status</h5>
                                <div id="webhook-status">
                                    <span class="badge badge-secondary">Not tested</span>
                                </div>
                            </div>
                        </div>

                        <!-- Configuration Steps -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Setup Instructions</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <ol>
                                            <li>
                                                <strong>Go to Chatwoot Webhook Settings:</strong><br>
                                                <a href="https://app.chatwoot.com/app/accounts/131300/settings/integrations/webhook" 
                                                   target="_blank" class="btn btn-sm btn-primary mt-1">
                                                    <i class="fas fa-external-link-alt"></i> Open Webhook Settings
                                                </a>
                                            </li>
                                            <li class="mt-3">
                                                <strong>Add New Webhook:</strong><br>
                                                • URL: <code>{{ route('admin.customer.support.webhook') }}</code><br>
                                                • Events: Select the events you want to receive (recommended: all)
                                            </li>
                                            <li class="mt-3">
                                                <strong>Generate Webhook Secret (Optional):</strong><br>
                                                • Generate a secret key for verification<br>
                                                • Add it to your .env file as: <code>CHATWOOT_WEBHOOK_SECRET=your_secret</code>
                                            </li>
                                            <li class="mt-3">
                                                <strong>Test the webhook using the button below</strong>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Webhook -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Test Webhook</h5>
                                <button onclick="testWebhook()" class="btn btn-success">
                                    <i class="fas fa-play"></i> Send Test Webhook
                                </button>
                                <div id="test-results" class="mt-3" style="display: none;">
                                    <div id="test-content"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Webhook Events -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Supported Events</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Event</th>
                                                <th>Description</th>
                                                <th>Action in Dashboard</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>conversation_created</code></td>
                                                <td>New conversation started</td>
                                                <td>Update conversation count, show notification</td>
                                            </tr>
                                            <tr>
                                                <td><code>message_created</code></td>
                                                <td>New message received</td>
                                                <td>Real-time message display, notification</td>
                                            </tr>
                                            <tr>
                                                <td><code>conversation_status_changed</code></td>
                                                <td>Conversation status updated</td>
                                                <td>Update status badges, refresh stats</td>
                                            </tr>
                                            <tr>
                                                <td><code>conversation_updated</code></td>
                                                <td>Conversation details changed</td>
                                                <td>Refresh conversation details</td>
                                            </tr>
                                            <tr>
                                                <td><code>contact_created</code></td>
                                                <td>New contact added</td>
                                                <td>Update contact count</td>
                                            </tr>
                                            <tr>
                                                <td><code>contact_updated</code></td>
                                                <td>Contact details updated</td>
                                                <td>Refresh contact information</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Webhook Logs -->
                        <div class="row">
                            <div class="col-12">
                                <h5>Recent Webhook Events</h5>
                                <button onclick="loadRecentWebhooks()" class="btn btn-outline-primary btn-sm mb-3">
                                    <i class="fas fa-refresh"></i> Refresh Logs
                                </button>
                                <div id="webhook-logs">
                                    <div class="text-center py-3">
                                        <p class="text-muted">Click refresh to load recent webhook events</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuration Status -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Current Configuration</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Webhook Secret</span>
                                        @if(config('services.chatwoot.webhook_secret'))
                                            <span class="badge badge-success">✓ Configured</span>
                                        @else
                                            <span class="badge badge-warning">⚠ Not configured</span>
                                        @endif
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>API Token</span>
                                        @if(config('services.chatwoot.api_token'))
                                            <span class="badge badge-success">✓ Configured</span>
                                        @else
                                            <span class="badge badge-danger">✗ Missing</span>
                                        @endif
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Account ID</span>
                                        <span class="badge badge-info">{{ config('services.chatwoot.account_id') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function copyWebhookUrl() {
    const urlInput = document.getElementById('webhook-url');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy text: ', err);
    }
}

async function testWebhook() {
    const resultsDiv = document.getElementById('test-results');
    const contentDiv = document.getElementById('test-content');
    
    resultsDiv.style.display = 'block';
    contentDiv.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Sending test webhook...';
    
    try {
        // Send a test webhook payload
        const testPayload = {
            event: 'test_webhook',
            timestamp: Date.now(),
            test: true,
            conversation: {
                id: 999999,
                status: 'open'
            },
            message: {
                content: 'This is a test webhook from Laravel dashboard',
                message_type: 0
            }
        };

        const response = await fetch('{{ route("admin.customer.support.webhook") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(testPayload)
        });
        
        if (response.ok) {
            const data = await response.json();
            contentDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle"></i> Webhook test successful!</h6>
                    <small>Response: ${JSON.stringify(data)}</small>
                </div>
            `;
            
            document.getElementById('webhook-status').innerHTML = 
                '<span class="badge badge-success">✓ Working</span>';
        } else {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
    } catch (error) {
        contentDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fas fa-times-circle"></i> Webhook test failed</h6>
                <small>Error: ${error.message}</small>
            </div>
        `;
        
        document.getElementById('webhook-status').innerHTML = 
            '<span class="badge badge-danger">✗ Failed</span>';
    }
}

async function loadRecentWebhooks() {
    const logsDiv = document.getElementById('webhook-logs');
    
    logsDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Loading...</div>';
    
    try {
        const response = await fetch('{{ route("admin.customer.support.webhook.logs") ?? "#" }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.logs && data.logs.length > 0) {
                logsDiv.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Event</th>
                                    <th>Conversation ID</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.logs.map(log => `
                                    <tr>
                                        <td>${new Date(log.timestamp).toLocaleString()}</td>
                                        <td><code>${log.event}</code></td>
                                        <td>${log.conversation_id || 'N/A'}</td>
                                        <td>${log.details || ''}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                logsDiv.innerHTML = '<div class="text-center py-3"><p class="text-muted">No recent webhook events found</p></div>';
            }
        } else {
            throw new Error('Failed to load webhook logs');
        }
    } catch (error) {
        logsDiv.innerHTML = `
            <div class="alert alert-warning">
                <small>Unable to load webhook logs: ${error.message}</small>
            </div>
        `;
    }
}

// Auto-refresh webhook status on page load
document.addEventListener('DOMContentLoaded', function() {
    // You can add auto-check logic here
});
</script>
@endsection