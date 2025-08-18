<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SupportController extends Controller
{
    private $apiUrl;
    private $apiToken;
    private $accountId;
    private $webhookSecret;

    public function __construct()
    {
        $this->apiUrl = config('services.chatwoot.api_url');
        $this->apiToken = config('services.chatwoot.api_token');
        $this->accountId = config('services.chatwoot.account_id');
        $this->webhookSecret = config('services.chatwoot.webhook_secret');
    }

    /** Display the customer support dashboard */
    public function index()
    {
        return view('admin.content.customer-support');
    }

    /** Verify webhook signature */
    private function verifyWebhookSignature(Request $request)
    {
        if (!$this->webhookSecret) {
            return true; // No secret means skip
        }

        $payload = $request->all();
        if (isset($payload['test']) && $payload['test'] === true) {
            Log::info('Skipping signature verification for test webhook');
            return true;
        }

        $signature = $request->header('X-Chatwoot-Signature');
        if (!$signature) {
            Log::warning('No X-Chatwoot-Signature header found');
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $this->webhookSecret);
        return hash_equals($signature, $expectedSignature);
    }

    /** Store webhook log entry (keep recent 50) */
    private function storeWebhookLog($event, $payload)
    {
        try {
            $logEntry = [
                'timestamp' => now()->toISOString(),
                'event' => $event,
                'conversation_id' => $payload['conversation']['id'] ?? null,
                'message_id' => $payload['message']['id'] ?? null,
                'contact_id' => $payload['contact']['id'] ?? null,
                'details' => $this->getEventDetails($event, $payload),
                'payload' => $payload
            ];

            $logs = Cache::get('webhook_logs', []);
            array_unshift($logs, $logEntry);
            $logs = array_slice($logs, 0, 50);
            Cache::put('webhook_logs', $logs, 3600);

            // Optional: Write to file for persistent log
            $logFile = storage_path('logs/chatwoot_webhooks.log');
            file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);

        } catch (\Exception $e) {
            Log::error('Failed to store webhook log: ' . $e->getMessage());
        }
    }

    /** Get event details string for logs */
    private function getEventDetails($event, $payload)
    {
        switch ($event) {
            case 'conversation_created':
                return "New conversation from " . ($payload['conversation']['meta']['sender']['name'] ?? 'Unknown');
            case 'message_created':
                $messageType = $this->normalizeMessageType($payload['message']['message_type'] ?? 1);
                $direction = $messageType === 0 ? 'Incoming' : 'Outgoing';
                return "{$direction} message: " . substr($payload['message']['content'] ?? '', 0, 50);
            case 'conversation_status_changed':
                return "Status changed to: " . ($payload['conversation']['status'] ?? 'unknown');
            case 'contact_created':
                return "New contact: " . ($payload['contact']['name'] ?? $payload['contact']['email'] ?? 'Unknown');
            default:
                return "Event processed";
        }
    }

    /**
     * Normalize message type to consistent integer format
     */
    private function normalizeMessageType($messageType)
    {
        if (is_string($messageType)) {
            switch (strtolower($messageType)) {
                case 'incoming':
                case '0':
                    return 0;
                case 'outgoing':
                case '1':
                    return 1;
                default:
                    return 1; // Default to outgoing
            }
        }
        
        return (int) $messageType;
    }

    /**
     * Store real-time event with better deduplication
     */
    private function storeRealtimeEvent(string $type, array $data)
    {
        try {
            // Generate unique ID based on event type and content
            $contentHash = md5(json_encode($data));
            $eventId = time() . '_' . substr($contentHash, 0, 8);
            
            // Check for duplicate events
            $events = Cache::get('realtime_events', []);
            $duplicateCheck = array_filter($events, function($event) use ($eventId) {
                return ($event['id'] ?? '') === $eventId;
            });
            
            if (!empty($duplicateCheck)) {
                Log::debug('Skipping duplicate real-time event', ['event_id' => $eventId, 'type' => $type]);
                return null;
            }
            
            $event = [
                'id' => $eventId,
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString(),
                'server_time' => time()
            ];

            // Add to beginning of array
            array_unshift($events, $event);

            // Keep only last 100 events
            $events = array_slice($events, 0, 100);

            // Store in cache for 2 hours
            Cache::put('realtime_events', $events, 7200);
            
            Log::debug('Real-time event stored', [
                'event_id' => $eventId,
                'type' => $type,
                'conversation_id' => $data['conversation_id'] ?? null,
                'message_id' => $data['message']['id'] ?? null,
                'total_events' => count($events)
            ]);

            return $event;
        } catch (\Exception $e) {
            Log::error('Failed to store real-time event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get recent events with better filtering
     */
    private function getRecentEvents($lastEventId = 0)
    {
        $events = Cache::get('realtime_events', []);
        
        // Filter events newer than lastEventId and sort by ID
        $filteredEvents = array_filter($events, function($event) use ($lastEventId) {
            return ($event['id'] ?? 0) > $lastEventId;
        });
        
        // Sort by ID to ensure proper order
        usort($filteredEvents, function($a, $b) {
            return ($a['id'] ?? 0) - ($b['id'] ?? 0);
        });
        
        return $filteredEvents;
    }

    /**
     * Helper method to send SSE formatted messages
     */
    private function sendSSEMessage(array $data)
    {
        $json = json_encode($data);
        echo "data: {$json}\n\n";
        
        // Force flush output
        if (ob_get_level()) {
            ob_flush();
        }
        flush();
    }

    /**
     * Enhanced SSE stream with better error handling and connection management
     */
    public function streamEvents(Request $request)
    {
        // Set headers for SSE
        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Disable nginx buffering
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Cache-Control'
        ];

        return response()->stream(function () {
            // Set longer execution time for SSE
            set_time_limit(0);
            ignore_user_abort(false);

            try {
                // Send initial connection event
                $this->sendSSEMessage([
                    'type' => 'connected',
                    'timestamp' => now()->toISOString(),
                    'server_time' => time()
                ]);

                $lastEventId = 0;
                $heartbeatCounter = 0;
                $errorCount = 0;
                $maxErrors = 5;
                
                while (true) {
                    // Check if client disconnected
                    if (connection_aborted()) {
                        Log::info('[SSE] Client disconnected');
                        break;
                    }

                    try {
                        // Get recent events
                        $events = $this->getRecentEvents($lastEventId);
                        
                        foreach ($events as $event) {
                            if (connection_aborted()) {
                                break 2;
                            }
                            
                            $this->sendSSEMessage($event);
                            $lastEventId = max($lastEventId, $event['id'] ?? 0);
                        }
                        
                        // Send heartbeat every 30 seconds (15 iterations * 2 seconds)
                        $heartbeatCounter++;
                        if ($heartbeatCounter >= 15) {
                            $this->sendSSEMessage([
                                'type' => 'heartbeat',
                                'timestamp' => now()->toISOString()
                            ]);
                            $heartbeatCounter = 0;
                        }
                        
                        // Reset error count on successful iteration
                        $errorCount = 0;
                        
                    } catch (\Exception $e) {
                        $errorCount++;
                        Log::error('[SSE] Stream error: ' . $e->getMessage());
                        
                        $this->sendSSEMessage([
                            'type' => 'error',
                            'message' => 'Stream error occurred',
                            'timestamp' => now()->toISOString()
                        ]);
                        
                        // Break if too many consecutive errors
                        if ($errorCount >= $maxErrors) {
                            Log::error('[SSE] Too many consecutive errors, closing stream');
                            break;
                        }
                    }
                    
                    // Wait 2 seconds before next check
                    sleep(2);
                }
                
            } catch (\Exception $e) {
                Log::error('[SSE] Fatal stream error: ' . $e->getMessage());
                $this->sendSSEMessage([
                    'type' => 'fatal_error',
                    'message' => 'Stream encountered fatal error',
                    'timestamp' => now()->toISOString()
                ]);
            }
        }, 200, $headers);
    }

    /**
     * Enhanced webhook handler with better logging and error handling
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();
            $headers = $request->headers->all();
            
            Log::info('Chatwoot Webhook Received', [
                'event' => $payload['event'] ?? 'unknown',
                'conversation_id' => $payload['conversation']['id'] ?? null,
                'message_id' => $payload['message']['id'] ?? null,
                'user_agent' => $headers['user-agent'][0] ?? '',
                'ip' => $request->ip(),
                'payload_size' => strlen($request->getContent())
            ]);

            $isTestWebhook = isset($payload['test']) && $payload['test'] === true;

            // Verify webhook signature if configured
            if ($this->webhookSecret && !$isTestWebhook && !$this->verifyWebhookSignature($request)) {
                Log::warning('Webhook signature verification failed', [
                    'received_signature' => $request->header('X-Chatwoot-Signature'),
                    'ip' => $request->ip()
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            if (!$this->webhookSecret) {
                Log::info('Webhook secret not configured - skipping signature verification');
            }

            $event = $payload['event'] ?? 'unknown';

            // Store webhook log
            $this->storeWebhookLog($event, $payload);

            // Handle different webhook events
            switch ($event) {
                case 'conversation_created':
                    $this->handleConversationCreated($payload);
                    break;
                case 'conversation_updated':
                    $this->handleConversationUpdated($payload);
                    break;
                case 'conversation_status_changed':
                    $this->handleConversationStatusChanged($payload);
                    break;
                case 'message_created':
                    $this->handleMessageCreated($payload);
                    break;
                case 'message_updated':
                    $this->handleMessageUpdated($payload);
                    break;
                case 'contact_created':
                    $this->handleContactCreated($payload);
                    break;
                case 'contact_updated':
                    $this->handleContactUpdated($payload);
                    break;
                case 'conversation_typing_on':
                case 'conversation_typing_off':
                    $this->handleTypingEvent($payload);
                    break;
                case 'webwidget_triggered':
                    $this->handleWebwidgetTriggered($payload);
                    break;
                case 'test_webhook':
                    Log::info('Test webhook received successfully', [
                        'source' => $isTestWebhook ? 'Laravel Dashboard' : 'External',
                        'conversation_id' => $payload['conversation']['id'] ?? null
                    ]);
                    break;
                default:
                    Log::info("Unhandled webhook event: {$event}", [
                        'payload_keys' => array_keys($payload)
                    ]);
            }

            // Clear relevant caches
            $this->clearRelevantCaches($event);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'event' => $event,
                'timestamp' => now()->toISOString(),
                'test_mode' => $isTestWebhook,
                'processing_time_ms' => round((microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true))) * 1000, 2)
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Webhook processing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enhanced message created handler with better duplicate prevention
     */
    private function handleMessageCreated(array $payload)
    {
        $messageTypeRaw = $payload['message']['message_type'] ?? 1;
        $conversationId = $payload['conversation']['id'] ?? null;
        $message = $payload['message'] ?? [];
        $messageId = $message['id'] ?? null;
        
        // Skip if we don't have essential data
        if (!$conversationId || !$messageId) {
            Log::warning('Skipping message - missing conversation_id or message_id', [
                'conversation_id' => $conversationId,
                'message_id' => $messageId
            ]);
            return;
        }
        
        // Check for duplicate processing using cache with longer TTL
        $duplicateKey = "processed_message_{$messageId}";
        if (Cache::has($duplicateKey)) {
            Log::debug('Skipping duplicate message processing', ['message_id' => $messageId]);
            return;
        }
        
        // Mark as processed for 30 minutes
        Cache::put($duplicateKey, true, 1800);
        
        // Normalize message type to integer
        $messageType = $this->normalizeMessageType($messageTypeRaw);
        $messageDirection = ($messageType === 0) ? 'incoming' : 'outgoing';
        
        Log::info('Processing new message', [
            'conversation_id' => $conversationId,
            'message_id' => $messageId,
            'message_type_raw' => $messageTypeRaw,
            'message_type_normalized' => $messageType,
            'direction' => $messageDirection,
            'content_preview' => substr($message['content'] ?? '', 0, 100),
            'sender' => $message['sender']['name'] ?? 'Unknown'
        ]);

        // Prepare normalized message data
        $normalizedMessage = array_merge($message, [
            'message_type' => $messageType, // Integer for consistency
            'direction' => $messageDirection // String for easy frontend handling
        ]);

        // Store real-time event for SSE
        $realtimeEvent = $this->storeRealtimeEvent('message_created', [
            'conversation_id' => $conversationId,
            'message' => $normalizedMessage,
            'conversation' => $payload['conversation'] ?? null,
        ]);

        if ($realtimeEvent) {
            Log::info('Real-time message event stored', [
                'event_id' => $realtimeEvent['id'],
                'message_id' => $messageId,
                'conversation_id' => $conversationId
            ]);
        }

        // Handle specific message type processing
        if ($messageType === 0) {
            $this->handleIncomingMessage($payload);
        } else {
            $this->handleOutgoingMessage($payload);
        }
        
        // Update conversation counts
        $this->updateConversationCounts();
    }

    /**
     * Enhanced incoming message handler
     */
    private function handleIncomingMessage(array $payload)
    {
        $conversationId = $payload['conversation']['id'] ?? null;
        $message = $payload['message'] ?? [];

        Log::info('Incoming message processed', [
            'conversation_id' => $conversationId,
            'message_id' => $message['id'] ?? null,
            'content_length' => strlen($message['content'] ?? ''),
            'sender' => $message['sender']['name'] ?? 'Unknown'
        ]);

        // Update conversation counts due to new incoming message
        $this->updateConversationCounts();
    }

    /**
     * Enhanced outgoing message handler
     */
    private function handleOutgoingMessage(array $payload)
    {
        $conversationId = $payload['conversation']['id'] ?? null;
        $message = $payload['message'] ?? [];

        Log::info('Outgoing message processed', [
            'conversation_id' => $conversationId,
            'message_id' => $message['id'] ?? null,
            'content_length' => strlen($message['content'] ?? ''),
            'sender' => $message['sender']['name'] ?? 'Unknown'
        ]);
    }

    private function handleConversationCreated($payload)
    {
        Log::info('New conversation created', [
            'conversation_id' => $payload['conversation']['id'] ?? null,
            'sender' => $payload['conversation']['meta']['sender']['name'] ?? 'Unknown'
        ]);

        $this->storeRealtimeEvent('new_conversation', [
            'conversation' => $payload['conversation'] ?? null
        ]);

        $this->updateConversationCounts();
    }

    private function handleConversationUpdated($payload)
    {
        Log::info('Conversation updated', [
            'conversation_id' => $payload['conversation']['id'] ?? null
        ]);

        $this->storeRealtimeEvent('conversation_updated', [
            'conversation' => $payload['conversation'] ?? null
        ]);
    }

    private function handleConversationStatusChanged($payload)
    {
        Log::info('Conversation status changed', [
            'conversation_id' => $payload['conversation']['id'] ?? null,
            'new_status' => $payload['conversation']['status'] ?? null
        ]);

        $this->storeRealtimeEvent('conversation_status_changed', [
            'conversation' => $payload['conversation'] ?? null
        ]);

        $this->updateConversationCounts();
    }

    private function handleMessageUpdated($payload)
    {
        Log::info('Message updated', [
            'message_id' => $payload['message']['id'] ?? null,
            'conversation_id' => $payload['conversation']['id'] ?? null
        ]);
    }

    private function handleContactCreated($payload)
    {
        Log::info('New contact created', [
            'contact_id' => $payload['contact']['id'] ?? null,
            'name' => $payload['contact']['name'] ?? null,
            'email' => $payload['contact']['email'] ?? null
        ]);
        $this->updateContactCounts();
    }

    private function handleContactUpdated($payload)
    {
        Log::info('Contact updated', [
            'contact_id' => $payload['contact']['id'] ?? null
        ]);
    }

    private function handleTypingEvent($payload)
    {
        $conversationId = $payload['conversation']['id'] ?? null;
        $event = $payload['event'] ?? null;
        $user = $payload['user'] ?? null;

        if ($conversationId) {
            $typingData = [
                'is_typing' => $event === 'conversation_typing_on',
                'user' => $user,
                'timestamp' => now()->toISOString(),
            ];

            Cache::put("typing_status_{$conversationId}", $typingData, 10);

            $this->storeRealtimeEvent('typing_update', [
                'conversation_id' => $conversationId,
                'typing_data' => $typingData,
            ]);
        }

        Log::debug('Typing event', [
            'conversation_id' => $conversationId,
            'event' => $event,
            'user' => $user['name'] ?? 'Unknown'
        ]);
    }

    private function handleWebwidgetTriggered($payload)
    {
        Log::info('Web widget opened', [
            'conversation_id' => $payload['conversation']['id'] ?? null
        ]);
    }

    /** Cache clearing helpers */
    private function clearRelevantCaches($event)
    {
        foreach (['stats', 'conversations', 'contacts'] as $cache) {
            Cache::forget("chatwoot_{$cache}");
        }
    }

    private function updateConversationCounts()
    {
        Cache::forget('chatwoot_stats');
    }

    private function updateContactCounts()
    {
        Cache::forget('chatwoot_contacts');
    }

    /**
     * Get recent webhook logs
     */
    public function getWebhookLogs()
    {
        try {
            $logs = Cache::get('webhook_logs', []);
            
            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching webhook logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch webhook logs',
                'logs' => []
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        try {
            if (!$this->apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'API token not configured',
                    'data' => [
                        'open_conversations' => 'N/A',
                        'resolved_conversations' => 'N/A',
                        'total_contacts' => 'N/A',
                        'pending_conversations' => 'N/A'
                    ]
                ]);
            }

            // Check cache first
            $cacheKey = 'chatwoot_stats';
            $cachedStats = Cache::get($cacheKey);
            
            if ($cachedStats) {
                return response()->json([
                    'success' => true,
                    'data' => $cachedStats,
                    'cached' => true
                ]);
            }

            // Get all conversations to calculate stats
            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/conversations");

            if ($response->successful()) {
                $data = $response->json();
                $meta = $data['data']['meta'] ?? [];
                
                // Get total contacts
                $contactsResponse = Http::withHeaders([
                    'api_access_token' => $this->apiToken,
                ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/contacts");

                $totalContacts = 0;
                if ($contactsResponse->successful()) {
                    $contactsData = $contactsResponse->json();
                    $totalContacts = $contactsData['meta']['count'] ?? count($contactsData['payload'] ?? []);
                }

                $statsData = [
                    'open_conversations' => $meta['mine_count'] ?? 0,
                    'resolved_conversations' => $this->getResolvedTodayCount(),
                    'total_contacts' => $totalContacts,
                    'pending_conversations' => $meta['unassigned_count'] ?? 0
                ];

                // Cache for 5 minutes
                Cache::put($cacheKey, $statsData, 300);

                return response()->json([
                    'success' => true,
                    'data' => $statsData
                ]);
            } else {
                throw new \Exception('API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Chatwoot stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch stats: ' . $e->getMessage(),
                'data' => [
                    'open_conversations' => 'Error',
                    'resolved_conversations' => 'Error',
                    'total_contacts' => 'Error',
                    'pending_conversations' => 'Error'
                ]
            ], 500);
        }
    }

    /**
     * Get conversations with optional status filter
     */
    public function getConversations(Request $request)
    {
        try {
            if (!$this->apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'API token not configured',
                    'data' => []
                ]);
            }

            $status = $request->get('status', 'open');
            $conversations = $this->getConversationsByStatus($status);

            return response()->json([
                'success' => true,
                'data' => $conversations
            ]);

        } catch (\Exception $e) {
            Log::error('Chatwoot conversations error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch conversations: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages($conversationId)
    {
        try {
            if (!$this->apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'API token not configured',
                    'data' => []
                ]);
            }

            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/conversations/{$conversationId}/messages");

            if ($response->successful()) {
                $messages = $response->json();
                // Return the payload array which contains the messages
                return response()->json([
                    'success' => true,
                    'data' => $messages['payload'] ?? []
                ]);
            } else {
                throw new \Exception('API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Chatwoot messages error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch messages: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Send a message to a conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        try {
            if (!$this->apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'API token not configured'
                ]);
            }

            $request->validate([
                'content' => 'required|string'
            ]);

            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->post("{$this->apiUrl}/accounts/{$this->accountId}/conversations/{$conversationId}/messages", [
                'content' => $request->content,
                'message_type' => 'outgoing'
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Message sent successfully via API', [
                    'conversation_id' => $conversationId,
                    'message_id' => $responseData['id'] ?? null,
                    'content_length' => strlen($request->content)
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $responseData
                ]);
            } else {
                throw new \Exception('API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Chatwoot send message error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            if (!$this->apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'API token not configured'
                ]);
            }

            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/conversations?status=open&page=1");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'API connection successful',
                    'data' => [
                        'status_code' => $response->status(),
                        'account_id' => $this->accountId
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API connection failed',
                    'error' => $response->body(),
                    'status_code' => $response->status()
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper method to get conversations by status
     */
    private function getConversationsByStatus($status)
    {
        try {
            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/conversations", [
                'status' => $status
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Based on your API response structure
                return $data['data']['payload'] ?? [];
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error("Error fetching {$status} conversations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get resolved conversations count for today
     */
    private function getResolvedTodayCount()
    {
        try {
            // This is a simplified version - you might need to adjust based on actual API
            $response = Http::withHeaders([
                'api_access_token' => $this->apiToken,
            ])->timeout(10)->get("{$this->apiUrl}/accounts/{$this->accountId}/conversations", [
                'status' => 'resolved'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return count($data['data']['payload'] ?? []);
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Redirect to specific Chatwoot sections
     */
    public function redirect($section = 'dashboard')
    {
        $baseUrl = 'https://app.chatwoot.com/app/accounts/' . $this->accountId;
        
        $allowedSections = [
            'dashboard' => '/dashboard',
            'conversations' => '/conversations',
            'contacts' => '/contacts',
            'reports' => '/reports',
            'settings' => '/settings',
            'inbox' => '/inbox'
        ];

        $path = $allowedSections[$section] ?? '/dashboard';
        
        return redirect()->away($baseUrl . $path);
    }

    /**
     * Get live conversation updates
     */
    public function getLiveUpdates(Request $request)
    {
        try {
            $conversationId = $request->get('conversation_id');
            $lastMessageId = $request->get('last_message_id', 0);
            
            if (!$conversationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation ID required'
                ]);
            }

            // Get latest messages for the conversation
            $messages = $this->getMessages($conversationId);
            $messagesData = json_decode($messages->getContent(), true);
            
            if ($messagesData['success']) {
                $newMessages = array_filter($messagesData['data'], function($message) use ($lastMessageId) {
                    return ($message['id'] ?? 0) > $lastMessageId;
                });

                return response()->json([
                    'success' => true,
                    'messages' => array_values($newMessages),
                    'conversation_id' => $conversationId,
                    'timestamp' => now()->toISOString()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch messages'
            ]);

        } catch (\Exception $e) {
            Log::error('Live updates error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get live updates'
            ]);
        }
    }

    /**
     * Get typing status for a conversation
     */
    public function getTypingStatus($conversationId)
    {
        try {
            $typingData = Cache::get("typing_status_{$conversationId}", []);
            
            return response()->json([
                'success' => true,
                'typing' => $typingData,
                'conversation_id' => $conversationId
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get typing status'
            ]);
        }
    }

    /**
     * Clear all cached data (for debugging)
     */
    public function clearCache()
    {
        try {
            // Clear all chatwoot related caches
            $cacheKeys = [
                'chatwoot_stats',
                'chatwoot_conversations', 
                'chatwoot_contacts',
                'realtime_events',
                'webhook_logs'
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            // Clear processed message cache (pattern-based)
            // Note: This is a simplified approach - in production you might want a more sophisticated cache clearing mechanism
            Log::info('Cache cleared manually');

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get system status and diagnostics
     */
    public function getStatus()
    {
        try {
            $eventCount = count(Cache::get('realtime_events', []));
            $logCount = count(Cache::get('webhook_logs', []));
            
            return response()->json([
                'success' => true,
                'status' => [
                    'api_configured' => !empty($this->apiToken),
                    'webhook_secret_configured' => !empty($this->webhookSecret),
                    'account_id' => $this->accountId,
                    'realtime_events_count' => $eventCount,
                    'webhook_logs_count' => $logCount,
                    'cache_driver' => config('cache.default'),
                    'server_time' => now()->toISOString(),
                    'php_memory_usage' => memory_get_usage(true),
                    'php_memory_peak' => memory_get_peak_usage(true)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get status: ' . $e->getMessage()
            ]);
        }
    }
}