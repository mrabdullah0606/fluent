@extends('teacher.master.master')
@section('title', 'Calendar - FluentAll')
@section('content')
    @push('teacherStyles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }

            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
            }

            .calendar-nav {
                background: #007bff;
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .calendar-nav:hover {
                background: #0056b3;
                transform: scale(1.1);
            }

            .calendar-day {
                width: 40px;
                height: 40px;
                border: none;
                background: transparent;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                margin: 2px;
            }

            .calendar-day:hover:not(.outside):not(:disabled) {
                background: #e9ecef;
                transform: scale(1.1);
            }

            .calendar-day.selected {
                background: #007bff;
                color: white;
            }

            .calendar-day.has-availability {
                background: #28a745;
                color: white;
            }

            .calendar-day.has-availability.selected {
                background: #155724;
            }

            .calendar-day.outside {
                color: #6c757d;
                opacity: 0.5;
            }

            .calendar-day:disabled {
                opacity: 0.3;
                cursor: not-allowed;
            }

            .time-slot {
                padding: 12px;
                margin-bottom: 8px;
                background: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #28a745;
                transition: all 0.3s ease;
            }

            .time-slot:hover {
                background: #e9ecef;
                transform: translateX(5px);
            }

            .btn-remove {
                background: none;
                border: none;
                color: #dc3545;
                padding: 5px;
                border-radius: 4px;
                transition: all 0.3s ease;
            }

            .btn-remove:hover {
                background: #dc3545;
                color: white;
            }

            .alert-floating {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                animation: slideInRight 0.3s ease;
            }

            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .loading {
                pointer-events: none;
                opacity: 0.7;
            }

            .time-picker-group {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }

            .modal-backdrop.show {
                opacity: 0.5;
            }

            .modal {
                z-index: 1050;
            }

            .modal-backdrop {
                z-index: 1040;
            }
        </style>
    @endpush
    <main class="flex-grow-1">
        <div class="bg-light flex-grow-1 p-4 p-md-5" style="background: transparent !important;">
            <div class="container-fluid">
                <div style="opacity: 1; transform: none;">
                    <h1 class="display-6 fw-bold text-white mb-4 d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-3" style="font-size: 2rem;"></i>
                        My Calendar & Availability
                    </h1>
                </div>

                <div class="row g-4 align-items-start">
                    <!-- Calendar Section -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-body p-2 p-md-4">
                                <div class="p-3 rounded w-100">
                                    <!-- Calendar Header -->
                                    <div class="calendar-header">
                                        <h4 class="mb-0 text-primary" id="currentMonth">July 2025</h4>
                                        <div class="d-flex gap-2">
                                            <button class="calendar-nav prev btn" onclick="previousMonth()">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button class="calendar-nav next btn" onclick="nextMonth()">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Calendar Grid -->
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="fw-normal text-muted">Su</th>
                                                    <th class="fw-normal text-muted">Mo</th>
                                                    <th class="fw-normal text-muted">Tu</th>
                                                    <th class="fw-normal text-muted">We</th>
                                                    <th class="fw-normal text-muted">Th</th>
                                                    <th class="fw-normal text-muted">Fr</th>
                                                    <th class="fw-normal text-muted">Sa</th>
                                                </tr>
                                            </thead>
                                            <tbody id="calendarBody">
                                                <!-- Calendar days will be generated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Availability Section -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h5 class="card-title mb-1 text-primary">
                                    <i class="fas fa-clock me-2"></i>Availability
                                </h5>
                                <p class="text-muted small mb-0" id="selectedDateText">Tuesday, July 29</p>
                            </div>
                            <div class="card-body">
                                <!-- Time Slots Container -->
                                <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                                    <div id="timeSlotsContainer">
                                        <div class="text-center py-4" id="slotsLoader" style="display: none;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <p class="text-muted text-center py-4" id="noSlotsMessage">
                                            No available slots for this day.
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#addTimeSlotsModal">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Time Slots
                                </button>
                                <button class="btn btn-outline-danger w-100" onclick="markDayUnavailable()">
                                    <i class="fas fa-times me-2"></i>
                                    Mark Full Day as Unavailable
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Add Time Slots Modal -->
    <div class="modal fade" id="addTimeSlotsModal" tabindex="-1" aria-labelledby="addTimeSlotsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary" id="addTimeSlotsModalLabel">
                        <i class="fas fa-clock me-2"></i>Add Multiple Time Slots
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted mb-4">
                        Quickly add a range of available times for the selected date.<br>
                        This will add slots every hour between your selected times.
                    </p>

                    <div class="time-picker-group mb-4">
                        <div class="row g-3">
                            <!-- Start Time -->
                            <div class="col-6">
                                <label class="form-label fw-medium">Start Time</label>
                                <div class="d-flex gap-2">
                                    <input type="time" class="form-control" id="startTime" value="08:00">
                                    <select class="form-select" id="startAmPm" style="max-width: 80px;">
                                        <option value="am">AM</option>
                                        <option value="pm">PM</option>
                                    </select>
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="col-6">
                                <label class="form-label fw-medium">End Time</label>
                                <div class="d-flex gap-2">
                                    <input type="time" class="form-control" id="endTime" value="17:00">
                                    <select class="form-select" id="endAmPm" style="max-width: 80px;">
                                        <option value="am">AM</option>
                                        <option value="pm" selected>PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addTimeSlots()" id="addSlotsBtn">
                        <i class="fas fa-plus me-2"></i>Add to Calendar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        // Fixed Calendar JavaScript with proper error handling and null checks

        // Configuration
        const API_BASE = '/teacher/availability';
        let currentDate = new Date();
        let selectedDate = new Date().getDate();
        let today = new Date();
        let monthlyAvailabilities = {};
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Ensure we start with today's date if we're in current month
        if (currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
            selectedDate = today.getDate();
        }

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        // Utility Functions
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) {
                console.error('Alert container not found');
                return;
            }

            const alertId = 'alert-' + Date.now();

            const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show alert-floating" role="alert" id="${alertId}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        `;

            alertContainer.insertAdjacentHTML('beforeend', alertHTML);

            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        function formatDate(date) {
            // Use local date components to avoid timezone issues
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function setLoading(element, loading) {
            if (!element) return;

            if (loading) {
                element.classList.add('loading');
            } else {
                element.classList.remove('loading');
            }
        }

        // API Functions
        async function apiRequest(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            };

            try {
                const response = await fetch(url, {
                    ...defaultOptions,
                    ...options
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    let errorMessage = `HTTP error! status: ${response.status}`;

                    try {
                        const errorData = JSON.parse(errorText);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        // If not JSON, use the text
                        errorMessage = errorText || errorMessage;
                    }

                    throw new Error(errorMessage);
                }

                const data = await response.json();
                return data;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        // Calendar Functions
        async function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const currentMonthElement = document.getElementById('currentMonth');
            if (currentMonthElement) {
                currentMonthElement.textContent = `${monthNames[month]} ${year}`;
            }

            // Load monthly availabilities
            await loadMonthlyAvailabilities(year, month + 1);

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            let html = '';
            let dayCount = 1;

            for (let week = 0; week < 6; week++) {
                html += '<tr class="text-center">';
                for (let day = 0; day < 7; day++) {
                    const cellIndex = week * 7 + day;
                    if (cellIndex < firstDay) {
                        const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
                        html +=
                            `<td><button class="calendar-day outside" onclick="selectDate(${prevDate}, -1)">${prevDate}</button></td>`;
                    } else if (dayCount <= daysInMonth) {
                        const isSelected = dayCount === selectedDate;
                        const dateKey =
                            `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                        const hasAvailability = monthlyAvailabilities[dateKey] && monthlyAvailabilities[dateKey]
                            .length > 0;

                        // Check if date is in the past
                        const currentDateObj = new Date(year, month, dayCount);
                        const todayObj = new Date();
                        todayObj.setHours(0, 0, 0, 0);
                        currentDateObj.setHours(0, 0, 0, 0);
                        const isPastDate = currentDateObj < todayObj;

                        const classes = [
                            'calendar-day',
                            isSelected ? 'selected' : '',
                            hasAvailability ? 'has-availability' : '',
                            isPastDate ? 'outside' : ''
                        ].filter(Boolean).join(' ');

                        const onClickHandler = isPastDate ? '' : `onclick="selectDate(${dayCount}, 0)"`;
                        html +=
                            `<td><button class="${classes}" ${onClickHandler} ${isPastDate ? 'disabled title="Past date"' : ''}>${dayCount}</button></td>`;
                        dayCount++;
                    } else {
                        const nextDate = dayCount - daysInMonth;
                        html +=
                            `<td><button class="calendar-day outside" onclick="selectDate(${nextDate}, 1)">${nextDate}</button></td>`;
                        dayCount++;
                    }
                }

                html += '</tr>';

                if (dayCount > daysInMonth && week >= 4) break;
            }

            const calendarBody = document.getElementById('calendarBody');
            if (calendarBody) {
                calendarBody.innerHTML = html;
            }

            updateSelectedDateText();
        }

        async function loadMonthlyAvailabilities(year, month) {
            try {
                const response = await apiRequest(`${API_BASE}/monthly?year=${year}&month=${month}`);
                monthlyAvailabilities = response.availabilities || {};
            } catch (error) {
                console.error('Error loading monthly availabilities:', error);
                monthlyAvailabilities = {};
            }
        }

        function selectDate(date, monthOffset) {
            if (monthOffset !== 0) {
                if (monthOffset === -1) {
                    previousMonth();
                } else {
                    nextMonth();
                }
                return;
            }

            // Check if the selected date is in the past
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), date);
            const todayObj = new Date();
            todayObj.setHours(0, 0, 0, 0);
            selectedDateObj.setHours(0, 0, 0, 0);

            if (selectedDateObj < todayObj) {
                showAlert('Cannot select past dates. Please choose today or a future date.', 'warning');
                return;
            }

            selectedDate = date;
            generateCalendar();
            displayTimeSlots();
        }

        async function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            await generateCalendar();
        }

        async function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            await generateCalendar();
        }

        function updateSelectedDateText() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];

            const selectedDateElement = document.getElementById('selectedDateText');
            if (selectedDateElement) {
                selectedDateElement.textContent = `${dayName}, ${monthName} ${selectedDate}`;
            }
        }

        // Time Slots Functions - FIXED
        async function displayTimeSlots() {
            const container = document.getElementById('timeSlotsContainer');
            if (!container) {
                console.error('Time slots container not found');
                return;
            }

            // Get or create loader and no slots message elements
            let loader = document.getElementById('slotsLoader');
            let noSlotsMessage = document.getElementById('noSlotsMessage');

            // Create elements if they don't exist
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'slotsLoader';
                loader.className = 'text-center py-4';
                loader.style.display = 'none';
                loader.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
                container.appendChild(loader);
            }

            if (!noSlotsMessage) {
                noSlotsMessage = document.createElement('p');
                noSlotsMessage.id = 'noSlotsMessage';
                noSlotsMessage.className = 'text-muted text-center py-4';
                noSlotsMessage.textContent = 'No available slots for this day.';
                noSlotsMessage.style.display = 'none';
                container.appendChild(noSlotsMessage);
            }

            // Clear container except for loader and no slots message
            Array.from(container.children).forEach(child => {
                if (child.id !== 'slotsLoader' && child.id !== 'noSlotsMessage') {
                    child.remove();
                }
            });

            // Show loader
            loader.style.display = 'block';
            noSlotsMessage.style.display = 'none';

            try {
                const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
                const dateString = formatDate(selectedDateObj);

                console.log('Fetching slots for date:', dateString);

                const response = await apiRequest(`${API_BASE}/date?date=${dateString}`);

                loader.style.display = 'none';

                if (!response.slots || response.slots.length === 0) {
                    noSlotsMessage.style.display = 'block';
                    return;
                }

                // Create slots container
                const slotsWrapper = document.createElement('div');
                slotsWrapper.className = 'time-slots-wrapper';

                response.slots.forEach((slot) => {
                    const statusIcon = slot.is_available ? 'fas fa-clock text-success' :
                        'fas fa-times-circle text-danger';
                    const statusText = slot.is_available ? '' : ' (Unavailable)';

                    const slotElement = document.createElement('div');
                    slotElement.className =
                        `time-slot d-flex justify-content-between align-items-center ${!slot.is_available ? 'opacity-50' : ''}`;
                    slotElement.id = `slot-${slot.id}`;
                    slotElement.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${statusIcon} me-2"></i>
                    <span class="fw-medium">${slot.formatted_range}${statusText}</span>
                </div>
                <button class="btn-remove" onclick="removeTimeSlot(${slot.id})" title="Remove this time slot">
                    <i class="fas fa-times"></i>
                </button>
            `;

                    slotsWrapper.appendChild(slotElement);
                });

                container.appendChild(slotsWrapper);

            } catch (error) {
                console.error('Error loading time slots:', error);
                loader.style.display = 'none';
                noSlotsMessage.textContent = 'Error loading time slots: ' + error.message;
                noSlotsMessage.style.display = 'block';
                showAlert('Error loading time slots: ' + error.message, 'danger');
            }
        }

        async function addTimeSlots() {
            const addBtn = document.getElementById('addSlotsBtn');
            if (!addBtn) {
                console.error('Add slots button not found');
                return;
            }

            const originalText = addBtn.innerHTML;

            try {
                addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                addBtn.disabled = true;

                const startTime = document.getElementById('startTime')?.value;
                const endTime = document.getElementById('endTime')?.value;
                const startAmPm = document.getElementById('startAmPm')?.value;
                const endAmPm = document.getElementById('endAmPm')?.value;

                if (!startTime || !endTime || !startAmPm || !endAmPm) {
                    throw new Error('Please fill in all time fields');
                }

                const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
                const dateString = formatDate(selectedDateObj);

                console.log('Adding slots for date:', dateString);
                console.log('Time range:', startTime, startAmPm, '-', endTime, endAmPm);

                const response = await apiRequest(`${API_BASE}/store`, {
                    method: 'POST',
                    body: JSON.stringify({
                        date: dateString,
                        start_time: startTime,
                        end_time: endTime,
                        start_ampm: startAmPm,
                        end_ampm: endAmPm
                    })
                });

                showAlert(`Successfully added ${response.slots_added} time slots!`, 'success');

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTimeSlotsModal'));
                if (modal) {
                    modal.hide();
                }

                // Refresh displays
                await displayTimeSlots();
                await generateCalendar();

            } catch (error) {
                console.error('Error adding time slots:', error);
                showAlert('Error adding time slots: ' + error.message, 'danger');
            } finally {
                addBtn.innerHTML = originalText;
                addBtn.disabled = false;
            }
        }

        async function removeTimeSlot(slotId) {
            if (!confirm('Are you sure you want to remove this time slot?')) {
                return;
            }

            try {
                await apiRequest(`${API_BASE}/${slotId}`, {
                    method: 'DELETE'
                });

                showAlert('Time slot removed successfully!', 'success');
                await displayTimeSlots();
                await generateCalendar();
            } catch (error) {
                console.error('Error removing time slot:', error);
                showAlert('Error removing time slot: ' + error.message, 'danger');
            }
        }

        async function markDayUnavailable() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];

            if (!confirm(
                    `Are you sure you want to mark ${dayName}, ${monthName} ${selectedDate} as unavailable? This will remove all existing time slots for this day.`
                )) {
                return;
            }

            try {
                const dateString = formatDate(selectedDateObj);

                await apiRequest(`${API_BASE}/mark-unavailable`, {
                    method: 'POST',
                    body: JSON.stringify({
                        date: dateString
                    })
                });

                showAlert('Day marked as unavailable successfully!', 'success');
                await displayTimeSlots();
                await generateCalendar();
            } catch (error) {
                console.error('Error marking day unavailable:', error);
                showAlert('Error marking day unavailable: ' + error.message, 'danger');
            }
        }

        // Initialize - FIXED
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('DOM Content Loaded - Initializing calendar...');

            try {
                // Ensure we start with today's date
                const today = new Date();
                currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
                selectedDate = today.getDate();

                console.log('Initial date:', currentDate, 'Selected date:', selectedDate);

                await generateCalendar();
                await displayTimeSlots();

                console.log('Calendar initialized successfully');
            } catch (error) {
                console.error('Error initializing calendar:', error);
                showAlert('Error initializing calendar: ' + error.message, 'danger');
            }
        });
    </script> --}}
    <script>
        // Configuration
        const API_BASE = '/teacher/availability';
        let currentDate = new Date();
        let selectedDate = new Date().getDate();
        let today = new Date();
        let monthlyAvailabilities = {};
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let lastGeneratedYear = null;
        let lastGeneratedMonth = null;
        const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        // Initialize Calendar
        if (document.readyState !== 'loading') {
            initCalendar();
        } else {
            document.addEventListener('DOMContentLoaded', initCalendar);
        }

        async function initCalendar() {
            console.log('Initializing calendar...');
            try {
                today = new Date();
                currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
                selectedDate = today.getDate();
                await generateCalendar();
                await displayTimeSlots();
                setupKeyboardNavigation();
                console.log('Calendar initialized successfully');
            } catch (error) {
                console.error('Error initializing calendar:', error);
                showAlert('Error initializing calendar: ' + error.message, 'danger');
            }
        }

        // Utility Functions
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) {
                console.error('Alert container not found');
                return;
            }

            // Clean up old alerts
            const oldAlerts = alertContainer.querySelectorAll('.alert-floating');
            if (oldAlerts.length > 3) {
                oldAlerts[0].remove();
            }

            const alertId = 'alert-' + Date.now();
            const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show alert-floating" role="alert" id="${alertId}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

            alertContainer.insertAdjacentHTML('beforeend', alertHTML);

            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function setLoading(element, loading) {
            if (!element) return;
            element.classList.toggle('loading', loading);
        }

        // API Functions
        async function apiRequest(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-User-TimeZone': userTimeZone
                },
                credentials: 'same-origin'
            };

            try {
                const response = await fetch(url, {
                    ...defaultOptions,
                    ...options
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    let errorMessage = `HTTP error! status: ${response.status}`;

                    try {
                        const errorData = JSON.parse(errorText);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = errorText || errorMessage;
                    }

                    throw new Error(errorMessage);
                }

                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        // Calendar Functions
        async function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Check if we need to regenerate or just update
            if (lastGeneratedYear === year && lastGeneratedMonth === month) {
                updateSelectedDateStyles();
                return;
            }

            lastGeneratedYear = year;
            lastGeneratedMonth = month;

            const currentMonthElement = document.getElementById('currentMonth');
            if (currentMonthElement) {
                currentMonthElement.textContent = `${monthNames[month]} ${year}`;
            }

            // Load monthly availabilities
            await loadMonthlyAvailabilities(year, month + 1);

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            let html = '';
            let dayCount = 1;

            for (let week = 0; week < 6; week++) {
                html += '<tr class="text-center" role="row">';
                for (let day = 0; day < 7; day++) {
                    const cellIndex = week * 7 + day;
                    if (cellIndex < firstDay) {
                        const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
                        html += `
                    <td role="gridcell">
                        <button class="calendar-day outside" 
                                onclick="selectDate(${prevDate}, -1)" 
                                tabindex="-1"
                                aria-label="Previous month day ${prevDate}">
                            ${prevDate}
                        </button>
                    </td>`;
                    } else if (dayCount <= daysInMonth) {
                        const dateKey =
                            `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                        const hasAvailability = monthlyAvailabilities[dateKey] && monthlyAvailabilities[dateKey]
                            .length > 0;

                        // Check if date is in the past
                        const currentDateObj = new Date(year, month, dayCount);
                        const todayObj = new Date();
                        todayObj.setHours(0, 0, 0, 0);
                        currentDateObj.setHours(0, 0, 0, 0);
                        const isPastDate = currentDateObj < todayObj;
                        const isSelected = dayCount === selectedDate;

                        const classes = [
                            'calendar-day',
                            isSelected ? 'selected' : '',
                            hasAvailability ? 'has-availability' : '',
                            isPastDate ? 'outside' : ''
                        ].filter(Boolean).join(' ');

                        const onClickHandler = isPastDate ? '' : `onclick="selectDate(${dayCount}, 0)"`;
                        const tabIndex = isPastDate ? '-1' : isSelected ? '0' : '-1';
                        const ariaLabel =
                            `${dayCount} ${monthNames[month]} ${year}${hasAvailability ? ', has availability' : ''}`;
                        const ariaSelected = isSelected ? 'true' : 'false';

                        html += `
                    <td role="gridcell">
                        <button class="${classes}" 
                                ${onClickHandler} 
                                ${isPastDate ? 'disabled title="Past date"' : ''}
                                tabindex="${tabIndex}"
                                aria-label="${ariaLabel}"
                                aria-selected="${ariaSelected}">
                            ${dayCount}
                        </button>
                    </td>`;
                        dayCount++;
                    } else {
                        const nextDate = dayCount - daysInMonth;
                        html += `
                    <td role="gridcell">
                        <button class="calendar-day outside" 
                                onclick="selectDate(${nextDate}, 1)" 
                                tabindex="-1"
                                aria-label="Next month day ${nextDate}">
                            ${nextDate}
                        </button>
                    </td>`;
                        dayCount++;
                    }
                }

                html += '</tr>';

                if (dayCount > daysInMonth && week >= 4) break;
            }

            const calendarBody = document.getElementById('calendarBody');
            if (calendarBody) {
                calendarBody.innerHTML = html;
            }

            updateSelectedDateText();
        }

        function updateSelectedDateStyles() {
            const calendarBody = document.getElementById('calendarBody');
            if (!calendarBody) return;

            // Remove selected class from all days
            const allDays = calendarBody.querySelectorAll('.calendar-day');
            allDays.forEach(day => {
                day.classList.remove('selected');
                day.setAttribute('aria-selected', 'false');
                day.tabIndex = day.classList.contains('outside') ? '-1' : '-1';
            });

            // Add selected class to current selected date
            const selectedDay = Array.from(allDays).find(day =>
                parseInt(day.textContent) === selectedDate &&
                !day.classList.contains('outside')
            );

            if (selectedDay) {
                selectedDay.classList.add('selected');
                selectedDay.setAttribute('aria-selected', 'true');
                selectedDay.tabIndex = '0';
            }

            updateSelectedDateText();
        }

        async function loadMonthlyAvailabilities(year, month) {
            try {
                const response = await apiRequest(`${API_BASE}/monthly?year=${year}&month=${month}`);
                monthlyAvailabilities = response.availabilities || {};
            } catch (error) {
                console.error('Error loading monthly availabilities:', error);
                monthlyAvailabilities = {};
            }
        }

        function selectDate(date, monthOffset) {
            if (monthOffset !== 0) {
                if (monthOffset === -1) {
                    previousMonth();
                } else {
                    nextMonth();
                }
                return;
            }

            // Check if the selected date is in the past
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), date);
            const todayObj = new Date();
            todayObj.setHours(0, 0, 0, 0);
            selectedDateObj.setHours(0, 0, 0, 0);

            if (selectedDateObj < todayObj) {
                showAlert('Cannot select past dates. Please choose today or a future date.', 'warning');
                return;
            }

            selectedDate = date;
            generateCalendar();
            displayTimeSlots();
        }

        async function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            await generateCalendar();
        }

        async function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            await generateCalendar();
        }

        function updateSelectedDateText() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];

            const selectedDateElement = document.getElementById('selectedDateText');
            if (selectedDateElement) {
                selectedDateElement.textContent = `${dayName}, ${monthName} ${selectedDate}`;
            }
        }

        // Time Slots Functions
        async function displayTimeSlots() {
            const container = document.getElementById('timeSlotsContainer');
            if (!container) {
                console.error('Time slots container not found');
                return;
            }

            // Get or create loader and no slots message elements
            let loader = document.getElementById('slotsLoader');
            let noSlotsMessage = document.getElementById('noSlotsMessage');

            // Create elements if they don't exist
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'slotsLoader';
                loader.className = 'text-center py-4';
                loader.style.display = 'none';
                loader.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
                container.appendChild(loader);
            }

            if (!noSlotsMessage) {
                noSlotsMessage = document.createElement('p');
                noSlotsMessage.id = 'noSlotsMessage';
                noSlotsMessage.className = 'text-muted text-center py-4';
                noSlotsMessage.textContent = 'No available slots for this day.';
                noSlotsMessage.style.display = 'none';
                container.appendChild(noSlotsMessage);
            }

            // Clear container except for loader and no slots message
            Array.from(container.children).forEach(child => {
                if (child.id !== 'slotsLoader' && child.id !== 'noSlotsMessage') {
                    child.remove();
                }
            });

            // Show loader
            loader.style.display = 'block';
            noSlotsMessage.style.display = 'none';

            try {
                const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
                const dateString = formatDate(selectedDateObj);

                const response = await apiRequest(`${API_BASE}/date?date=${dateString}`);

                loader.style.display = 'none';

                if (!response.slots || response.slots.length === 0) {
                    noSlotsMessage.style.display = 'block';
                    return;
                }

                // Create slots container
                const slotsWrapper = document.createElement('div');
                slotsWrapper.className = 'time-slots-wrapper';
                slotsWrapper.setAttribute('role', 'list');

                response.slots.forEach((slot) => {
                    const statusIcon = slot.is_available ? 'fas fa-clock text-success' :
                        'fas fa-times-circle text-danger';
                    const statusText = slot.is_available ? '' : ' (Unavailable)';

                    const slotElement = document.createElement('div');
                    slotElement.className =
                        `time-slot d-flex justify-content-between align-items-center ${!slot.is_available ? 'opacity-50' : ''}`;
                    slotElement.id = `slot-${slot.id}`;
                    slotElement.setAttribute('role', 'listitem');
                    slotElement.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${statusIcon} me-2" aria-hidden="true"></i>
                    <span class="fw-medium">${slot.formatted_range}${statusText}</span>
                </div>
                <button class="btn-remove" onclick="removeTimeSlot(${slot.id})" title="Remove this time slot" aria-label="Remove time slot">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            `;

                    slotsWrapper.appendChild(slotElement);
                });

                container.appendChild(slotsWrapper);

            } catch (error) {
                console.error('Error loading time slots:', error);
                loader.style.display = 'none';
                noSlotsMessage.textContent = 'Error loading time slots: ' + error.message;
                noSlotsMessage.style.display = 'block';
                showAlert('Error loading time slots: ' + error.message, 'danger');
            }
        }

        async function addTimeSlots() {
            const addBtn = document.getElementById('addSlotsBtn');
            if (!addBtn) {
                console.error('Add slots button not found');
                return;
            }

            const originalText = addBtn.innerHTML;

            try {
                addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                addBtn.disabled = true;

                const startTime = document.getElementById('startTime')?.value;
                const endTime = document.getElementById('endTime')?.value;
                const startAmPm = document.getElementById('startAmPm')?.value;
                const endAmPm = document.getElementById('endAmPm')?.value;

                if (!startTime || !endTime || !startAmPm || !endAmPm) {
                    throw new Error('Please fill in all time fields');
                }

                // Validate time range
                const startTimeValue = new Date(`2000-01-01T${startTime}`);
                const endTimeValue = new Date(`2000-01-01T${endTime}`);

                if (startTimeValue >= endTimeValue) {
                    throw new Error('End time must be after start time');
                }

                const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
                const dateString = formatDate(selectedDateObj);

                const response = await apiRequest(`${API_BASE}/store`, {
                    method: 'POST',
                    body: JSON.stringify({
                        date: dateString,
                        start_time: startTime,
                        end_time: endTime,
                        start_ampm: startAmPm,
                        end_ampm: endAmPm
                    })
                });

                showAlert(`Successfully added ${response.slots_added} time slots!`, 'success');

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTimeSlotsModal'));
                if (modal) {
                    modal.hide();
                }

                // Refresh displays
                await displayTimeSlots();
                await generateCalendar();

            } catch (error) {
                console.error('Error adding time slots:', error);
                showAlert('Error adding time slots: ' + error.message, 'danger');
            } finally {
                addBtn.innerHTML = originalText;
                addBtn.disabled = false;
            }
        }

        async function removeTimeSlot(slotId) {
            if (!confirm('Are you sure you want to remove this time slot?')) {
                return;
            }

            try {
                await apiRequest(`${API_BASE}/${slotId}`, {
                    method: 'DELETE'
                });

                showAlert('Time slot removed successfully!', 'success');
                await displayTimeSlots();
                await generateCalendar();
            } catch (error) {
                console.error('Error removing time slot:', error);
                showAlert('Error removing time slot: ' + error.message, 'danger');
            }
        }

        async function markDayUnavailable() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];

            if (!confirm(
                    `Are you sure you want to mark ${dayName}, ${monthName} ${selectedDate} as unavailable? This will remove all existing time slots for this day.`
                )) {
                return;
            }

            try {
                const dateString = formatDate(selectedDateObj);

                await apiRequest(`${API_BASE}/mark-unavailable`, {
                    method: 'POST',
                    body: JSON.stringify({
                        date: dateString
                    })
                });

                showAlert('Day marked as unavailable successfully!', 'success');
                await displayTimeSlots();
                await generateCalendar();
            } catch (error) {
                console.error('Error marking day unavailable:', error);
                showAlert('Error marking day unavailable: ' + error.message, 'danger');
            }
        }

        // Keyboard Navigation
        function setupKeyboardNavigation() {
            document.addEventListener('keydown', function(e) {
                const calendarDay = e.target.closest('.calendar-day:not(.outside)');
                if (!calendarDay) return;

                const currentDay = parseInt(calendarDay.textContent);
                const allDays = Array.from(document.querySelectorAll('.calendar-day:not(.outside)'));
                const currentIndex = allDays.findIndex(day => parseInt(day.textContent) === currentDay);

                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    const nextDay = allDays[currentIndex + 1];
                    if (nextDay) {
                        nextDay.focus();
                        selectDate(parseInt(nextDay.textContent), 0);
                    }
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const prevDay = allDays[currentIndex - 1];
                    if (prevDay) {
                        prevDay.focus();
                        selectDate(parseInt(prevDay.textContent), 0);
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const upDay = allDays[currentIndex - 7];
                    if (upDay) {
                        upDay.focus();
                        selectDate(parseInt(upDay.textContent), 0);
                    }
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const downDay = allDays[currentIndex + 7];
                    if (downDay) {
                        downDay.focus();
                        selectDate(parseInt(downDay.textContent), 0);
                    }
                } else if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    calendarDay.click();
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    const firstDay = allDays[0];
                    if (firstDay) {
                        firstDay.focus();
                        selectDate(parseInt(firstDay.textContent), 0);
                    }
                } else if (e.key === 'End') {
                    e.preventDefault();
                    const lastDay = allDays[allDays.length - 1];
                    if (lastDay) {
                        lastDay.focus();
                        selectDate(parseInt(lastDay.textContent), 0);
                    }
                }
            });
        }

        // Make functions available globally
        window.selectDate = selectDate;
        window.previousMonth = previousMonth;
        window.nextMonth = nextMonth;
        window.addTimeSlots = addTimeSlots;
        window.removeTimeSlot = removeTimeSlot;
        window.markDayUnavailable = markDayUnavailable;
    </script>
@endsection
