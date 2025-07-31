<div class="col-md-3 mb-3">
    <div class="list-group">
        <a href="{{ route('teacher.settings.index') }}" class="text-decoration-none">
            <button
                class="list-group-item list-group-item-action {{ request()->routeIs('teacher.settings.index') ? 'active bg-danger border-0' : '' }}">
                <i class="bi bi-gear me-2"></i>
                Lesson Management
            </button>
        </a>
        <a href="{{ route('teacher.bookings') }}" class="text-decoration-none">
            <button
                class="list-group-item list-group-item-action {{ request()->routeIs('teacher.bookings') ? 'active bg-danger border-0' : '' }}">
                <i class="bi bi-calendar-check me-2"></i>
                Booking Rules
            </button>
        </a>
    </div>
</div>
