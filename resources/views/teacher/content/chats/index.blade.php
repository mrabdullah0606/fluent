@extends('teacher.master.master')
@section('title', 'Chats - FluentAll')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        .chat-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .chat-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .message-enter {
            animation: messageSlideIn 0.3s ease-out;
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background: #f3f4f6;
            border-radius: 18px;
            margin: 8px 0;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #9ca3af;
            animation: typing 1.4s infinite ease-in-out;
            margin: 0 1px;
        }

        .typing-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0s;
        }

        @keyframes typing {

            0%,
            80%,
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .online-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
        }

        .message-status {
            display: inline-flex;
            align-items: center;
            margin-left: 4px;
        }

        .message-status svg {
            width: 12px;
            height: 12px;
        }
    </style>
@endpush

@section('content')
    <main class="flex-grow">
        <div class="h-[calc(100vh-65px)] flex bg-white">
            <!-- Sidebar with chat list -->
            <div class="w-1/3 border-r border-gray-200 flex flex-col bg-gray-50">
                <!-- Sidebar Header -->
                <div class="p-4 border-b bg-white">
                    <h2 class="text-xl font-bold text-foreground flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Messages
                        @if (isset($users) && $users->count() > 0)
                            <span class="ml-2 bg-primary text-primary-foreground text-xs px-2 py-1 rounded-full">
                                {{ $users->count() }}
                            </span>
                        @endif
                    </h2>

                    <!-- Search Box -->
                    <div class="relative mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input id="searchInput"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                            placeholder="Search conversations..." value="">
                        <button id="clearSearch"
                            class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground hover:text-foreground hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M15 9l-6 6"></path>
                                <path d="M9 9l6 6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Chat Users List -->
                <div class="flex-grow overflow-y-auto chat-scrollbar" id="chatUsersList">
                    @if (isset($users) && $users->count() > 0)
                        @foreach ($users as $chatUser)
                            <div class="chat-user-item flex items-center p-3 cursor-pointer hover:bg-gray-100 transition-colors border-b border-gray-100 {{ isset($user) && $user->id == $chatUser->id ? 'bg-primary/10 active border-l-4 border-l-primary' : '' }}"
                                data-user-id="{{ $chatUser->id }}" data-user-name="{{ $chatUser->name }}"
                                data-user-email="{{ $chatUser->email }}">

                                <!-- User Avatar -->
                                <div class="relative flex shrink-0 overflow-hidden rounded-full h-12 w-12 mr-3">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary font-semibold">
                                        {{ strtoupper(substr($chatUser->name, 0, 1)) }}
                                    </span>

                                    <!-- Unread Badge -->
                                    @if (isset($chatUser->unread_count) && $chatUser->unread_count > 0)
                                        <span
                                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold unread-badge">
                                            {{ $chatUser->unread_count > 9 ? '9+' : $chatUser->unread_count }}
                                        </span>
                                    @endif

                                    <!-- Online Indicator (you can implement online status logic) -->
                                    <div class="online-indicator"></div>
                                </div>

                                <!-- User Info -->
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="font-semibold text-foreground truncate pr-2">{{ $chatUser->name }}</p>
                                        <p class="text-xs text-muted-foreground flex-shrink-0">
                                            @if (isset($chatUser->latest_message))
                                                {{ $chatUser->latest_message->created_at->diffForHumans() }}
                                            @else
                                                {{ $chatUser->updated_at->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-sm text-muted-foreground truncate pr-2 latest-message">
                                            @if (isset($chatUser->latest_message))
                                                @if ($chatUser->latest_message->sender_id == auth()->id())
                                                    <span class="text-primary">You: </span>
                                                @endif
                                                {{ Str::limit($chatUser->latest_message->message, 30) }}
                                            @else
                                                {{ $chatUser->email }}
                                            @endif
                                        </p>

                                        <!-- Message Status for sent messages -->
                                        @if (isset($chatUser->latest_message) && $chatUser->latest_message->sender_id == auth()->id())
                                            <div class="message-status">
                                                @if ($chatUser->latest_message->read_at)
                                                    <!-- Read -->
                                                    <svg class="text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                        <path
                                                            d="M12.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                    </svg>
                                                @else
                                                    <!-- Delivered -->
                                                    <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-lg font-medium">No conversations yet</p>
                            <p class="text-sm">Start a conversation to see it here</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Footer -->
                <div class="p-3 border-t bg-white">
                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                        <span>{{ auth()->user()->name }}</span>
                        <span class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                            Online
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-2/3 flex flex-col bg-gray-50" id="chatArea">
                @if (isset($user))
                    <!-- Chat Header -->
                    <div class="p-4 border-b bg-white flex items-center justify-between shadow-sm" id="chatHeader">
                        <div class="flex items-center">
                            <span class="relative flex shrink-0 overflow-hidden rounded-full h-10 w-10 mr-3">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                                <div class="online-indicator"></div>
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold" id="chatUserName">{{ $user->name }}</h3>
                                <p class="text-sm text-muted-foreground" id="chatUserStatus">
                                    <span class="typing-status hidden">typing...</span>
                                    <span class="online-status">Online</span>
                                </p>
                            </div>
                        </div>

                        <!-- Chat Actions -->
                        <div class="flex items-center space-x-2">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Voice call">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Video call">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2">
                                    </rect>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="More options">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesContainer"
                        class="flex-grow p-6 overflow-y-auto chat-scrollbar bg-gradient-to-b from-gray-50 to-white">
                        <div class="space-y-4" id="messagesList">
                            @if (isset($messages) && $messages->count() > 0)
                                @foreach ($messages as $message)
                                    <div
                                        class="flex items-end gap-2 {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        @if ($message->sender_id != auth()->id())
                                            <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                                                <span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary text-sm font-semibold">
                                                    {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                                </span>
                                            </span>
                                        @endif
                                        <div class="max-w-md group">
                                            <div class="p-3 rounded-xl shadow-sm {{ $message->sender_id == auth()->id() ? 'bg-primary text-primary-foreground' : 'bg-white border' }}"
                                                data-message-id="{{ $message->id }}">
                                                <p class="break-words">{{ $message->message }}</p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <small
                                                        class="text-xs opacity-70">{{ $message->created_at->format('g:i A') }}</small>
                                                    @if ($message->sender_id == auth()->id())
                                                        <div class="message-status ml-2">
                                                            @if ($message->read_at)
                                                                <svg class="text-blue-300" width="12" height="12"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                                    <path
                                                                        d="M12.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                                </svg>
                                                            @else
                                                                <svg class="text-gray-300" width="12" height="12"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">No messages yet. Start the conversation!</p>
                                </div>
                            @endif
                        </div>

                        <!-- Typing Indicator -->
                        <div id="typingIndicator" class="hidden flex items-end gap-2 justify-start">
                            <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary text-sm font-semibold"
                                    id="typingAvatar">
                                </span>
                            </span>
                            <div class="typing-indicator">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input Area -->
                    <div class="p-4 bg-white border-t" id="messageInputArea">
                        <div class="flex items-end space-x-2">
                            <!-- Attachment Button -->
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Attach file">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66L9.64 16.2a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                    </path>
                                </svg>
                            </button>

                            <!-- Message Input -->
                            <div class="flex-grow relative">
                                <textarea id="messageInput"
                                    class="flex min-h-[40px] max-h-[120px] w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 resize-none"
                                    placeholder="Type your message..." rows="1"></textarea>

                                <!-- Character Count -->
                                <div class="absolute bottom-1 right-2 text-xs text-muted-foreground">
                                    <span id="charCount">0</span>/1000
                                </div>
                            </div>

                            <!-- Emoji Button -->
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Add emoji">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                </svg>
                            </button>

                            <!-- Send Button -->
                            <button id="sendButton"
                                class="inline-flex items-center justify-center rounded-full text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 w-10 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                                    <path d="M22 2 11 13"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Quick Responses -->
                        <div class="flex flex-wrap gap-2 mt-2" id="quickResponses">
                            <button
                                class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                👍 Thanks!
                            </button>
                            <button
                                class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                👋 Hello
                            </button>
                            <button
                                class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                ✅ Okay
                            </button>
                            <button
                                class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                ❓ Can you help?
                            </button>
                        </div>
                    </div>
                @else
                    <!-- No Chat Selected -->
                    <div class="flex-grow flex items-center justify-center" id="noChatSelected">
                        <div class="text-center max-w-md mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round" class="mx-auto mb-6 text-gray-300">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-600 mb-3">Welcome to FluentAll Chat</h3>
                            <p class="text-gray-500 mb-4">Select a conversation from the sidebar to start chatting with
                                students and teachers.</p>
                            <div class="text-sm text-gray-400">
                                <p>💬 Send messages instantly</p>
                                <p>📁 Share files and documents</p>
                                <p>🔔 Get real-time notifications</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <div class="flex items-center">
                    <svg class="animate-spin h-6 w-6 mr-3 text-primary" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-gray-700">Loading chat...</span>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
    </main>
@endsection

@push('scripts')
    <script>
        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
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
                this.chatUserId = {{ isset($user) ? $user->id : 'null' }};
                this.currentChannel = null;
                this.typingTimer = null;
                this.isTyping = false;

                // DOM Elements
                this.messageInput = document.querySelector('#messageInput');
                this.sendButton = document.querySelector('#sendButton');
                this.messagesContainer = document.querySelector('#messagesContainer');
                this.messagesList = document.querySelector('#messagesList');
                this.chatArea = document.querySelector('#chatArea');
                this.loadingOverlay = document.querySelector('#loadingOverlay');
                this.searchInput = document.querySelector('#searchInput');
                this.clearSearchBtn = document.querySelector('#clearSearch');
                this.charCount = document.querySelector('#charCount');
                this.typingIndicator = document.querySelector('#typingIndicator');
                this.typingAvatar = document.querySelector('#typingAvatar');

                this.init();
            }

            init() {
                this.bindUserClicks();
                this.bindSearch();
                this.bindQuickResponses();
                this.setupMessageInput();

                if (this.chatUserId) {
                    this.subscribeToChannel();
                    this.bindSendMessage();
                    this.scrollToBottom();
                }

                // Initialize notifications
                this.requestNotificationPermission();
            }

            bindUserClicks() {
                const userItems = document.querySelectorAll('.chat-user-item');
                userItems.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        const userId = item.dataset.userId;
                        const userName = item.dataset.userName;
                        this.loadChat(userId, userName, item);
                    });
                });
            }

            bindSearch() {
                if (this.searchInput) {
                    this.searchInput.addEventListener('input', (e) => {
                        const searchTerm = e.target.value.toLowerCase().trim();
                        const userItems = document.querySelectorAll('.chat-user-item');

                        if (searchTerm) {
                            this.clearSearchBtn.classList.remove('hidden');
                        } else {
                            this.clearSearchBtn.classList.add('hidden');
                        }

                        userItems.forEach(item => {
                            const userName = item.dataset.userName.toLowerCase();
                            const userEmail = item.dataset.userEmail.toLowerCase();

                            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });

                    this.clearSearchBtn.addEventListener('click', () => {
                        this.searchInput.value = '';
                        this.clearSearchBtn.classList.add('hidden');
                        document.querySelectorAll('.chat-user-item').forEach(item => {
                            item.style.display = 'flex';
                        });
                    });
                }
            }

            bindQuickResponses() {
                const quickResponses = document.querySelectorAll('.quick-response');
                quickResponses.forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (this.messageInput) {
                            this.messageInput.value = btn.textContent.trim();
                            this.updateCharCount();
                            this.messageInput.focus();
                        }
                    });
                });
            }

            setupMessageInput() {
                if (this.messageInput) {
                    // Auto-resize textarea
                    this.messageInput.addEventListener('input', (e) => {
                        this.updateCharCount();
                        this.autoResize(e.target);
                        this.handleTyping();
                    });

                    // Handle paste events
                    this.messageInput.addEventListener('paste', (e) => {
                        setTimeout(() => {
                            this.updateCharCount();
                            this.autoResize(e.target);
                        }, 0);
                    });
                }
            }

            autoResize(textarea) {
                textarea.style.height = 'auto';
                const maxHeight = 120; // max-h-[120px]
                const newHeight = Math.min(textarea.scrollHeight, maxHeight);
                textarea.style.height = newHeight + 'px';
            }

            updateCharCount() {
                if (this.charCount && this.messageInput) {
                    const count = this.messageInput.value.length;
                    this.charCount.textContent = count;

                    if (count > 900) {
                        this.charCount.classList.add('text-red-500');
                    } else if (count > 800) {
                        this.charCount.classList.add('text-yellow-500');
                        this.charCount.classList.remove('text-red-500');
                    } else {
                        this.charCount.classList.remove('text-red-500', 'text-yellow-500');
                    }
                }
            }

            handleTyping() {
                if (!this.chatUserId || !this.currentChannel) return;

                if (!this.isTyping) {
                    this.isTyping = true;
                    this.currentChannel.trigger('client-typing', {
                        user_id: this.currentUserId,
                        user_name: '{{ auth()->user()->name }}',
                        typing: true
                    });
                }

                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.isTyping = false;
                    if (this.currentChannel) {
                        this.currentChannel.trigger('client-typing', {
                            user_id: this.currentUserId,
                            typing: false
                        });
                    }
                }, 1000);
            }

            async loadChat(userId, userName, clickedItem) {
                // Update active state
                document.querySelectorAll('.chat-user-item').forEach(item => {
                    item.classList.remove('bg-primary/10', 'active', 'border-l-4', 'border-l-primary');
                });
                clickedItem.classList.add('bg-primary/10', 'active', 'border-l-4', 'border-l-primary');

                // Show loading
                this.showLoading();

                try {
                    // Unsubscribe from current channel
                    if (this.currentChannel) {
                        pusher.unsubscribe(this.currentChannel.name);
                    }

                    // Load messages via AJAX
                    const response = await fetch(`/{{ auth()->user()->role }}/chat/${userId}/messages`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.chatUserId = parseInt(userId);
                        this.updateChatArea(result.user, result.messages);
                        this.subscribeToChannel();
                        this.bindSendMessage();

                        // Clear unread count
                        const unreadBadge = clickedItem.querySelector('.unread-badge');
                        if (unreadBadge) {
                            unreadBadge.remove();
                        }

                        this.showToast('Chat loaded successfully', 'success');
                    } else {
                        this.showToast('Failed to load chat', 'error');
                    }
                } catch (error) {
                    console.error('Error loading chat:', error);
                    this.showToast('Network error. Please try again.', 'error');
                } finally {
                    this.hideLoading();
                }
            }

            updateChatArea(user, messages) {
                // Update chat header
                const noChatSelected = document.querySelector('#noChatSelected');
                if (noChatSelected) {
                    noChatSelected.remove();
                }

                // Create or update chat elements
                let chatHeader = document.querySelector('#chatHeader');

                if (!chatHeader) {
                    this.chatArea.innerHTML = this.createChatAreaHTML(user);
                    this.updateDOMReferences();
                } else {
                    // Update existing header
                    document.querySelector('#chatUserName').textContent = user.name;
                    document.querySelector('#chatHeader span span').textContent = user.name.charAt(0).toUpperCase();
                }

                // Clear and populate messages
                this.messagesList.innerHTML = '';
                messages.forEach(message => {
                    this.addMessageToChat(message, false, false);
                });

                // Update URL without page reload
                const newUrl = `/{{ auth()->user()->role }}/chat/${user.id}`;
                history.pushState({}, '', newUrl);

                this.scrollToBottom();
            }

            createChatAreaHTML(user) {
                return `
                    <!-- Chat Header -->
                    <div class="p-4 border-b bg-white flex items-center justify-between shadow-sm" id="chatHeader">
                        <div class="flex items-center">
                            <span class="relative flex shrink-0 overflow-hidden rounded-full h-10 w-10 mr-3">
                                <span class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary font-semibold">
                                    ${user.name.charAt(0).toUpperCase()}
                                </span>
                                <div class="online-indicator"></div>
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold" id="chatUserName">${user.name}</h3>
                                <p class="text-sm text-muted-foreground" id="chatUserStatus">
                                    <span class="typing-status hidden">typing...</span>
                                    <span class="online-status">Online</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Chat Actions -->
                        <div class="flex items-center space-x-2">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Voice call">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Video call">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="More options">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesContainer" class="flex-grow p-6 overflow-y-auto chat-scrollbar bg-gradient-to-b from-gray-50 to-white">
                        <div class="space-y-4" id="messagesList"></div>
                        
                        <!-- Typing Indicator -->
                        <div id="typingIndicator" class="hidden flex items-end gap-2 justify-start">
                            <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                                <span class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary text-sm font-semibold" id="typingAvatar"></span>
                            </span>
                            <div class="typing-indicator">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input Area -->
                    <div class="p-4 bg-white border-t" id="messageInputArea">
                        <div class="flex items-end space-x-2">
                            <!-- Attachment Button -->
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Attach file">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66L9.64 16.2a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                </svg>
                            </button>

                            <!-- Message Input -->
                            <div class="flex-grow relative">
                                <textarea id="messageInput"
                                    class="flex min-h-[40px] max-h-[120px] w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 resize-none"
                                    placeholder="Type your message..." 
                                    rows="1"></textarea>
                                
                                <!-- Character Count -->
                                <div class="absolute bottom-1 right-2 text-xs text-muted-foreground">
                                    <span id="charCount">0</span>/1000
                                </div>
                            </div>

                            <!-- Emoji Button -->
                            <button class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Add emoji">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                </svg>
                            </button>

                            <!-- Send Button -->
                            <button id="sendButton"
                                class="inline-flex items-center justify-center rounded-full text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 w-10 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                                    <path d="M22 2 11 13"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Quick Responses -->
                        <div class="flex flex-wrap gap-2 mt-2" id="quickResponses">
                            <button class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                👍 Thanks!
                            </button>
                            <button class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                👋 Hello
                            </button>
                            <button class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                ✅ Okay
                            </button>
                            <button class="quick-response px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                ❓ Can you help?
                            </button>
                        </div>
                    </div>
                `;
            }

            updateDOMReferences() {
                this.messageInput = document.querySelector('#messageInput');
                this.sendButton = document.querySelector('#sendButton');
                this.messagesContainer = document.querySelector('#messagesContainer');
                this.messagesList = document.querySelector('#messagesList');
                this.charCount = document.querySelector('#charCount');
                this.typingIndicator = document.querySelector('#typingIndicator');
                this.typingAvatar = document.querySelector('#typingAvatar');

                // Re-bind events for new elements
                this.setupMessageInput();
                this.bindQuickResponses();
            }

            subscribeToChannel() {
                if (!this.chatUserId) return;

                const user1 = Math.min(this.currentUserId, this.chatUserId);
                const user2 = Math.max(this.currentUserId, this.chatUserId);
                const channelName = `private-chat.${user1}.${user2}`;

                console.log('Subscribing to channel:', channelName);

                this.currentChannel = pusher.subscribe(channelName);

                // Message received
                this.currentChannel.bind('message.sent', (data) => {
                    console.log('Message received:', data);
                    this.addMessageToChat(data, false, true);

                    // Show browser notification if page is not visible
                    if (document.hidden && data.sender_id !== this.currentUserId) {
                        this.showBrowserNotification(data.sender_name, data.message);
                    }
                });

                // Typing events
                this.currentChannel.bind('client-typing', (data) => {
                    if (data.user_id !== this.currentUserId) {
                        this.handleTypingIndicator(data);
                    }
                });

                this.currentChannel.bind('pusher:subscription_error', (error) => {
                    console.error('Subscription error:', error);
                    this.showToast('Connection error. Please refresh the page.', 'error');
                });

                this.currentChannel.bind('pusher:subscription_succeeded', () => {
                    console.log('Successfully subscribed to chat channel');
                });
            }

            handleTypingIndicator(data) {
                if (data.typing) {
                    this.typingAvatar.textContent = data.user_name.charAt(0).toUpperCase();
                    this.typingIndicator.classList.remove('hidden');
                    document.querySelector('.typing-status').classList.remove('hidden');
                    document.querySelector('.online-status').classList.add('hidden');
                    this.scrollToBottom();
                } else {
                    this.typingIndicator.classList.add('hidden');
                    document.querySelector('.typing-status').classList.add('hidden');
                    document.querySelector('.online-status').classList.remove('hidden');
                }
            }

            bindSendMessage() {
                if (this.sendButton && this.messageInput) {
                    // Remove existing listeners by cloning elements
                    const newSendButton = this.sendButton.cloneNode(true);
                    const newMessageInput = this.messageInput.cloneNode(true);

                    this.sendButton.parentNode.replaceChild(newSendButton, this.sendButton);
                    this.messageInput.parentNode.replaceChild(newMessageInput, this.messageInput);

                    // Update references
                    this.sendButton = newSendButton;
                    this.messageInput = newMessageInput;

                    // Re-setup input events
                    this.setupMessageInput();

                    this.sendButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.sendMessage();
                    });

                    this.messageInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            this.sendMessage();
                        }
                    });
                }
            }

            async sendMessage() {
                const message = this.messageInput.value.trim();

                if (!message || !this.chatUserId || message.length > 1000) {
                    if (message.length > 1000) {
                        this.showToast('Message too long (max 1000 characters)', 'error');
                    }
                    return;
                }

                this.sendButton.disabled = true;
                const originalButtonContent = this.sendButton.innerHTML;
                this.sendButton.innerHTML = `
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;

                try {
                    const response = await fetch('{{ route(auth()->user()->role . '.chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            receiver_id: this.chatUserId,
                            message: message
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.messageInput.value = '';
                        this.updateCharCount();
                        this.autoResize(this.messageInput);
                        this.addMessageToChat(result.data, true, false);
                    } else {
                        console.error('Send message error:', result.error);
                        this.showToast('Failed to send message: ' + result.error, 'error');
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    this.showToast('Network error. Please try again.', 'error');
                } finally {
                    this.sendButton.disabled = false;
                    this.sendButton.innerHTML = originalButtonContent;
                    this.messageInput.focus();
                }
            }

            addMessageToChat(messageData, isOptimistic = false, isFromBroadcast = false) {
                // Avoid duplicate messages
                if (!isOptimistic && !isFromBroadcast && messageData.sender_id === this.currentUserId) {
                    return;
                }

                // Check if message already exists
                const existingMessage = document.querySelector(`[data-message-id="${messageData.id}"]`);
                if (existingMessage && !isOptimistic) {
                    return;
                }

                const isOwnMessage = messageData.sender_id === this.currentUserId;
                const messageHtml = this.createMessageHtml(messageData, isOwnMessage);

                const messageElement = document.createElement('div');
                messageElement.innerHTML = messageHtml;
                messageElement.firstElementChild.classList.add('message-enter');

                this.messagesList.appendChild(messageElement.firstElementChild);
                this.scrollToBottom();

                // Update sidebar with latest message
                this.updateSidebarMessage(messageData);
            }

            updateSidebarMessage(messageData) {
                const otherUserId = messageData.sender_id === this.currentUserId ? messageData.receiver_id : messageData
                    .sender_id;
                const userItem = document.querySelector(`[data-user-id="${otherUserId}"]`);

                if (userItem) {
                    // Update last message text
                    const messagePreview = userItem.querySelector('.latest-message');
                    if (messagePreview) {
                        const isOwnMessage = messageData.sender_id === this.currentUserId;
                        const prefix = isOwnMessage ? '<span class="text-primary">You: </span>' : '';
                        const truncatedMessage = messageData.message.length > 30 ?
                            messageData.message.substring(0, 30) + '...' :
                            messageData.message;
                        messagePreview.innerHTML = prefix + this.escapeHtml(truncatedMessage);
                    }

                    // Update timestamp
                    const timestamp = userItem.querySelector('.text-xs.text-muted-foreground');
                    if (timestamp) {
                        timestamp.textContent = this.formatTimeAgo(messageData.created_at);
                    }

                    // Move to top of list
                    const parent = userItem.parentNode;
                    parent.insertBefore(userItem, parent.firstChild);
                }
            }

            createMessageHtml(messageData, isOwnMessage) {
                const messageClass = isOwnMessage ? 'justify-end' : 'justify-start';
                const messageBg = isOwnMessage ? 'bg-primary text-primary-foreground' : 'bg-white border';
                const avatar = isOwnMessage ? '' : `
                    <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                        <span class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/40 text-primary text-sm font-semibold">
                            ${messageData.sender_name.charAt(0).toUpperCase()}
                        </span>
                    </span>
                `;

                const messageStatus = isOwnMessage ? `
                    <div class="message-status ml-2">
                        <svg class="text-gray-300" width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                    </div>
                ` : '';

                return `
                    <div class="flex items-end gap-2 ${messageClass}">
                        ${!isOwnMessage ? avatar : ''}
                        <div class="max-w-md group">
                            <div class="p-3 rounded-xl shadow-sm ${messageBg}" data-message-id="${messageData.id}">
                                <p class="break-words">${this.escapeHtml(messageData.message)}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <small class="text-xs opacity-70">${this.formatTime(messageData.created_at)}</small>
                                    ${messageStatus}
                                </div>
                            </div>
                        </div>
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
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            formatTimeAgo(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) {
                    return 'now';
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    return `${minutes}m ago`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    return `${hours}h ago`;
                } else {
                    return date.toLocaleDateString();
                }
            }

            scrollToBottom() {
                if (this.messagesContainer) {
                    setTimeout(() => {
                        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
                    }, 100);
                }
            }

            showLoading() {
                if (this.loadingOverlay) {
                    this.loadingOverlay.classList.remove('hidden');
                }
            }

            hideLoading() {
                if (this.loadingOverlay) {
                    this.loadingOverlay.classList.add('hidden');
                }
            }

            showToast(message, type = 'info') {
                const toastContainer = document.querySelector('#toastContainer');
                if (!toastContainer) return;

                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

                toast.className =
                    `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
                toast.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">${message}</span>
                        <button class="ml-3 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                `;

                toastContainer.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, 5000);
            }

            requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            }

            showBrowserNotification(senderName, message) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification(`New message from ${senderName}`, {
                        body: message.length > 50 ? message.substring(0, 50) + '...' : message,
                        icon: '/favicon.ico', // Replace with your app icon
                        tag: 'chat-message'
                    });

                    notification.onclick = () => {
                        window.focus();
                        notification.close();
                    };

                    // Auto close after 5 seconds
                    setTimeout(() => {
                        notification.close();
                    }, 5000);
                }
            }

            // Public method to handle window focus events
            handleWindowFocus() {
                // Mark messages as read when window gets focus
                if (this.chatUserId) {
                    // You can implement read receipt logic here
                }
            }

            // Public method to handle window blur events
            handleWindowBlur() {
                // Handle any cleanup when window loses focus
            }

            // Public method to destroy the chat manager
            destroy() {
                if (this.currentChannel) {
                    pusher.unsubscribe(this.currentChannel.name);
                }
                clearTimeout(this.typingTimer);
            }
        }

        // Global chat manager instance
        let chatManager;

        // Initialize chat when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            chatManager = new ChatManager();

            // Handle window focus/blur for read receipts and notifications
            window.addEventListener('focus', () => {
                if (chatManager) {
                    chatManager.handleWindowFocus();
                }
            });

            window.addEventListener('blur', () => {
                if (chatManager) {
                    chatManager.handleWindowBlur();
                }
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', (event) => {
                // Reload the page or handle navigation appropriately
                window.location.reload();
            });

            // Handle page unload
            window.addEventListener('beforeunload', () => {
                if (chatManager) {
                    chatManager.destroy();
                }
            });
        });

        // Handle connection status
        pusher.connection.bind('connected', () => {
            console.log('Connected to Pusher');
        });

        pusher.connection.bind('disconnected', () => {
            console.log('Disconnected from Pusher');
            if (chatManager) {
                chatManager.showToast('Connection lost. Attempting to reconnect...', 'error');
            }
        });

        pusher.connection.bind('state_change', (states) => {
            console.log('Connection state changed from', states.previous, 'to', states.current);
        });

        // Handle network online/offline status
        window.addEventListener('online', () => {
            if (chatManager) {
                chatManager.showToast('Back online', 'success');
            }
        });

        window.addEventListener('offline', () => {
            if (chatManager) {
                chatManager.showToast('You are offline', 'error');
            }
        });

        // Utility functions for global access
        window.ChatUtils = {
            formatTime: (timestamp) => {
                const date = new Date(timestamp);
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            formatTimeAgo: (timestamp) => {
                const date = new Date(timestamp);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) {
                    return 'now';
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    return `${minutes}m ago`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    return `${hours}h ago`;
                } else {
                    return date.toLocaleDateString();
                }
            },

            escapeHtml: (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        };
    </script>
@endpush
