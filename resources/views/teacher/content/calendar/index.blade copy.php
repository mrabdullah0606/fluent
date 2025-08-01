@extends('teacher.master.master')
@section('title', 'Calendar - FluentAll')
@section('content')
    @push('teacherStyles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            .calendar-day {
                width: 48px;
                height: 48px;
                border: none;
                background: transparent;
                font-size: 16px;
                margin: 2px;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .calendar-day:hover {
                background-color: #e9ecef;
            }

            .calendar-day.selected {
                background-color: #0d6efd;
                color: white;
            }

            .calendar-day.outside {
                color: #6c757d;
                opacity: 0.5;
            }

            .time-slot {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 8px 12px;
                margin-bottom: 8px;
                display: flex;
                justify-content: between;
                align-items: center;
            }

            .time-slot:hover {
                background-color: #e9ecef;
            }

            .btn-remove {
                background: none;
                border: none;
                color: #dc3545;
                font-size: 14px;
                padding: 2px 6px;
                border-radius: 4px;
            }

            .btn-remove:hover {
                background-color: #dc3545;
                color: white;
            }

            .modal-content {
                border-radius: 12px;
            }

            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            }

            .calendar-header {
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                margin-bottom: 20px;
            }

            .calendar-nav {
                position: absolute;
                background: transparent;
                border: 1px solid #dee2e6;
                width: 32px;
                height: 32px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0.7;
                transition: opacity 0.2s;
            }

            .calendar-nav:hover {
                opacity: 1;
                background-color: #e9ecef;
            }

            .calendar-nav.prev {
                left: 10px;
            }

            .calendar-nav.next {
                right: 10px;
            }
        </style>
    @endpush
    <main class="flex-grow-1">
        <div class="bg-light flex-grow-1 p-4 p-md-5">
            <div class="container-fluid">
                <div style="opacity: 1; transform: none;">
                    <h1 class="display-6 fw-bold text-dark mb-4 d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-3 text-primary" style="font-size: 2rem;"></i>
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
                                        <h4 class="mb-0" id="currentMonth">July 2025</h4>
                                        <button class="calendar-nav prev btn" onclick="previousMonth()">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="calendar-nav next btn" onclick="nextMonth()">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>

                                    <!-- Calendar Grid -->
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="fw-normal">Su</th>
                                                    <th class="fw-normal">Mo</th>
                                                    <th class="fw-normal">Tu</th>
                                                    <th class="fw-normal">We</th>
                                                    <th class="fw-normal">Th</th>
                                                    <th class="fw-normal">Fr</th>
                                                    <th class="fw-normal">Sa</th>
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
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-1">Availability</h5>
                                <p class="text-muted small mb-0" id="selectedDateText">Tuesday, July 29</p>
                            </div>
                            <div class="card-body">
                                <!-- Time Slots Container -->
                                <div class="mb-3" style="max-height: 240px; overflow-y: auto;">
                                    <div id="timeSlotsContainer">
                                        <p class="text-muted text-center py-4" id="noSlotsMessage">
                                            No available slots for this day.
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#addTimeSlotsModal">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Time Slots
                                </button>
                                <button class="btn btn-danger w-100" onclick="markDayUnavailable()">
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
                    <h5 class="modal-title fw-bold" id="addTimeSlotsModalLabel">Add Multiple Time Slots</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted mb-4">
                        Quickly add a range of available times for the selected date.<br>
                        This will add slots every hour.
                    </p>

                    <div class="row g-3">
                        <!-- Start Time -->
                        <div class="col-6">
                            <label class="form-label fw-medium">Start</label>
                            <div class="d-flex gap-2">
                                <input type="time" class="form-control" id="startTime" value="08:00">
                                <select class="form-select" id="startAmPm" style="max-width: 70px;">
                                    <option value="am">AM</option>
                                    <option value="pm">PM</option>
                                </select>
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="col-6">
                            <label class="form-label fw-medium">End</label>
                            <div class="d-flex gap-2">
                                <input type="time" class="form-control" id="endTime" value="17:00">
                                <select class="form-select" id="endAmPm" style="max-width: 70px;">
                                    <option value="am">AM</option>
                                    <option value="pm" selected>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addTimeSlots()">
                        Add to Calendar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentDate = new Date();
        let selectedDate = 29;
        let timeSlots = [];

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;

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
                        const selectedClass = isSelected ? 'selected' : '';
                        html +=
                            `<td><button class="calendar-day ${selectedClass}" onclick="selectDate(${dayCount}, 0)">${dayCount}</button></td>`;
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

            document.getElementById('calendarBody').innerHTML = html;
            updateSelectedDateText();
        }

        function selectDate(date, monthOffset) {
            if (monthOffset !== 0) {
                if (monthOffset === -1) {
                    previousMonth();
                } else {
                    nextMonth();
                }
            }

            selectedDate = date;
            generateCalendar();
            displayTimeSlots();
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        }

        function updateSelectedDateText() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];

            document.getElementById('selectedDateText').textContent =
                `${dayName}, ${monthName} ${selectedDate}`;
        }

        function addTimeSlots() {
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const startAmPm = document.getElementById('startAmPm').value;
            const endAmPm = document.getElementById('endAmPm').value;
            let startHour = parseInt(startTime.split(':')[0]);
            let startMinute = parseInt(startTime.split(':')[1]);
            let endHour = parseInt(endTime.split(':')[0]);
            let endMinute = parseInt(endTime.split(':')[1]);

            if (startAmPm === 'pm' && startHour !== 12) startHour += 12;
            if (startAmPm === 'am' && startHour === 12) startHour = 0;
            if (endAmPm === 'pm' && endHour !== 12) endHour += 12;
            if (endAmPm === 'am' && endHour === 12) endHour = 0;

            const selectedDateKey = `${currentDate.getFullYear()}-${currentDate.getMonth()}-${selectedDate}`;
            if (!timeSlots[selectedDateKey]) {
                timeSlots[selectedDateKey] = [];
            }
            let currentHour = startHour;
            while (currentHour < endHour) {
                const nextHour = currentHour + 1;

                const formatTime = (hour) => {
                    const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    return `${displayHour.toString().padStart(2, '0')}:00 ${ampm}`;
                };

                timeSlots[selectedDateKey].push({
                    id: `slot_${Date.now()}_${Math.floor(Math.random() * 1000)}`,
                    startTime: formatTime(currentHour),
                    endTime: formatTime(nextHour)
                });

                currentHour++;
            }

            displayTimeSlots();
            const modalElement = document.getElementById('addTimeSlotsModal');
            const addSlotsButton = document.querySelector('[data-bs-toggle="modal"][data-bs-target="#addTimeSlotsModal"]');
            const focusedElement = modalElement.querySelector(':focus');
            if (focusedElement) {
                focusedElement.blur();
            }
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();
            modalElement.addEventListener('hidden.bs.modal', function handleModalHidden() {
                if (addSlotsButton) {
                    addSlotsButton.focus();
                }
                setTimeout(() => {
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                }, 100);
                modalElement.removeEventListener('hidden.bs.modal', handleModalHidden);
            }, {
                once: true
            });
        }

        function displayTimeSlots() {
            const selectedDateKey = `${currentDate.getFullYear()}-${currentDate.getMonth()}-${selectedDate}`;
            const container = document.getElementById('timeSlotsContainer');
            if (!timeSlots[selectedDateKey] || timeSlots[selectedDateKey].length === 0) {
                container.innerHTML =
                    '<p class="text-muted text-center py-4" id="noSlotsMessage">No available slots for this day.</p>';
                return;
            }

            let html = '';
            timeSlots[selectedDateKey].forEach((slot, index) => {
                html += `
                    <div class="time-slot d-flex justify-content-between align-items-center" id="slot-${slot.id}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="fw-medium">${slot.startTime} - ${slot.endTime}</span>
                        </div>
                        <button class="btn-remove" onclick="removeTimeSlot('${slot.id}')" title="Remove this time slot">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function removeTimeSlot(slotId) {
            const selectedDateKey = `${currentDate.getFullYear()}-${currentDate.getMonth()}-${selectedDate}`;
            if (timeSlots[selectedDateKey]) {
                timeSlots[selectedDateKey] = timeSlots[selectedDateKey].filter(slot => slot.id != slotId);
                if (timeSlots[selectedDateKey].length === 0) {
                    delete timeSlots[selectedDateKey];
                }
                displayTimeSlots();
            }
        }

        function markDayUnavailable() {
            const selectedDateKey = `${currentDate.getFullYear()}-${currentDate.getMonth()}-${selectedDate}`;
            if (timeSlots[selectedDateKey]) {
                delete timeSlots[selectedDateKey];
            }
            displayTimeSlots();
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];
            console.log(`${dayName}, ${monthName} ${selectedDate} marked as unavailable`);
        }
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar();
            displayTimeSlots();
        });
    </script>
@endsection
