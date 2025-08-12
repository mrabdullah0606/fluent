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

    <a href="{{ route('admin.customer.support') }}" class="nav-link"><i class="bi bi-chat-left-text me-2"></i> Customer
        Support</a>

    <a href="{{ route('admin.wallet.withdrawals.index') }}" class="nav-link"><i class="bi bi-wallet me-2"></i>
        Withdrawals Management</a>
    {{-- <a href="" class="nav-link"><i class="bi bi-eye me-2"></i> Profile</a> --}}
    <a href="javascript:void(0);" class="nav-link text-danger"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</aside>
