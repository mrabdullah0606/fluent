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

    <!-- Admin Sidebar Link -->
    <a href="{{ route('admin.chat.index') }}" class="nav-link" id="admin-support-link">
        <i class="bi bi-chat-left-text me-2"></i>
        Customer Support
        @if (isset($unreadSupportCount) && $unreadSupportCount > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                id="support-badge">
                {{ $unreadSupportCount > 99 ? '99+' : $unreadSupportCount }}
            </span>
        @endif
    </a>

    <style>
        .notification-badge {
            font-size: 0.65rem;
            padding: 0.25em 0.4em;
            margin-left: -10px;
            margin-top: 10px;
            animation: pulse 2s infinite;
            z-index: 10;
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
        function updateAdminSupportCount() {
            // Only run on admin pages
            if (!window.location.pathname.includes('/admin/')) {
                return;
            }

            fetch('{{ route('admin.messages.combined-unread-count') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Admin support unread count:', data);

                    const supportLink = document.getElementById('admin-support-link');
                    let badge = document.getElementById('support-badge');

                    if (data.success && data.unread_count > 0) {
                        const countText = data.unread_count > 99 ? '99+' : data.unread_count.toString();

                        if (badge) {
                            // Update existing badge
                            badge.textContent = countText;
                        } else {
                            // Create new badge
                            badge = document.createElement('span');
                            badge.id = 'support-badge';
                            badge.className =
                                'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge';
                            badge.textContent = countText;
                            supportLink.appendChild(badge);
                        }
                    } else {
                        // Remove badge if no unread messages
                        if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching admin support count:', error);
                });
        }

        // Update count every 30 seconds
        setInterval(updateAdminSupportCount, 30000);

        // Update count when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateAdminSupportCount();
            }
        });

        // Update count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAdminSupportCount();
        });

        // Update count when navigating within admin area (for SPA-like behavior)
        window.addEventListener('popstate', function() {
            setTimeout(updateAdminSupportCount, 100);
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
