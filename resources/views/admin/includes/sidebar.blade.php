<aside class="sidebar">
    <h5 class="text-warning fw-bold">Admin Menu</h5>
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house me-2"></i> Dashboard
    </a>
    <!-- User Management -->
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') }}">
        <i class="bi bi-person-lines-fill me-2"></i> User Management
    </a>
    <a href="{{ route('admin.languages.index') }}"
        class="nav-link {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
        <i class="bi bi-person-lines-fill me-2"></i> Languages Management
    </a>

    <a href="{{ route('admin.applicants.index') }}"
        class="nav-link {{ request()->routeIs('admin.applicants.*') ? 'active' : '' }}">
        <i class="bi bi-person-lines-fill me-2"></i> Applicants Management
    </a>
    <a href="{{ route('admin.careers.index') }}"
        class="nav-link {{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">
        <i class="bi bi-briefcase-fill me-2"></i> Careers Management
    </a>

    <a href="{{ route('admin.chat.index') }}" class="nav-link"><i class="bi bi-chat-left-text me-2"></i>

        Customer
        Support
        @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                {{ $totalUnreadCount > 99 ? '99+' : $totalUnreadCount }}
            </span>
        @endif
    </a>


    <style>
        .notification-badge {
            font-size: 0.65rem;
            padding: 0.25em 0.4em;
            margin-left: -10px;
            margin-top: -5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .nav-link {
            position: relative;
        }
    </style>

    <script>
        // SIMPLIFIED - Single route approach
        function updateNavbarNotificationCount() {
            const currentPath = window.location.pathname;
            let unreadCountRoute;

            if (currentPath.includes('/teacher/')) {
                unreadCountRoute = '{{ route('teacher.messages.combined-unread-count') }}';
            } else if (currentPath.includes('/student/')) {
                unreadCountRoute = '{{ route('student.messages.combined-unread-count') }}';
            } else {
                // Default to teacher route
                unreadCountRoute = '{{ route('teacher.messages.combined-unread-count') }}';
            }

            fetch(unreadCountRoute)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Combined unread count response:', data);

                    const badge = document.querySelector('.notification-badge');
                    const messagesLink = document.querySelector('a[href*="chats"]');

                    if (data.unread_count > 0) {
                        if (badge) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                        } else if (messagesLink) {
                            // Create badge if it doesn't exist
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
                    console.error('Error fetching combined unread count:', error);
                });
        }

        // Update count every 30 seconds
        setInterval(updateNavbarNotificationCount, 30000);

        // Update count when page becomes visible (user switches back to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateNavbarNotificationCount();
            }
        });

        // Update count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNavbarNotificationCount();
        });
    </script>
    <a href="{{ route('admin.wallet.withdrawals.index') }}" class="nav-link"><i class="bi bi-wallet me-2"></i>
        Withdrawals Management</a>
    {{-- <a href="" class="nav-link"><i class="bi bi-eye me-2"></i> Profile</a> --}}
    <a href="javascript:void(0);" class="nav-link text-danger"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</aside>
