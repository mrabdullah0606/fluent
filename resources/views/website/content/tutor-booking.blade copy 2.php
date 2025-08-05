@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="min-h-screen bg-gray-50 py-8 md:py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <button
                    class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border bg-background hover:text-accent-foreground h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    <a href="{{ url()->previous() }}">Back</a>
                </button>

                <div class="text-center mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-16 w-16 mx-auto text-primary mb-3">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                        <line x1="16" x2="16" y1="2" y2="6"></line>
                        <line x1="8" x2="8" y1="2" y2="6"></line>
                        <line x1="3" x2="21" y1="10" y2="10"></line>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                        <path d="M16 18h.01"></path>
                    </svg>
                    <h1 class="text-3xl md:text-4xl font-bold text-foreground">Book Lesson with <span
                            class="text-gradient-yellow-red">{{ $teacher->name }}</span></h1>
                </div>
                <div id="alertContainer"></div>

                <div
                    class="bg-white p-6 md:p-8 rounded-xl shadow-xl border border-primary/20 grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-3">
                        <div class="rdp rounded-md border border-input bg-background shadow-sm p-4 w-full">
                            <div class="flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0">
                                <div class="space-y-4 rdp-caption_start rdp-caption_end">
                                    <div class="flex justify-center pt-1 relative items-center">
                                        <div class="text-sm font-medium" aria-live="polite" role="presentation"
                                            id="react-day-picker-49">Loading...</div>
                                        <div class="space-x-1 flex items-center">
                                            <button name="previous-month" aria-label="Go to previous month"
                                                class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute left-1"
                                                type="button" onclick="previousMonth()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                    <path d="m15 18-6-6 6-6"></path>
                                                </svg>
                                            </button>
                                            <button name="next-month" aria-label="Go to next month"
                                                class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute right-1"
                                                type="button" onclick="nextMonth()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                    <path d="m9 18 6-6-6-6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <table class="w-full border-collapse space-y-1" role="grid"
                                        aria-labelledby="react-day-picker-49">
                                        <thead class="rdp-head">
                                            <tr class="flex">
                                                @foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $day)
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="{{ $day }}">{{ $day }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="rdp-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-semibold text-foreground mb-3 text-lg">Available Time Slots</h3>
                            <div id="selectedDateDisplay" class="text-sm text-muted-foreground mb-3">
                                Select a date to see available times
                            </div>
                            <div id="availableSlotsContainer" class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto pr-2">
                                <!-- Slots will be loaded dynamically here -->
                            </div>
                            <div id="slotsLoader" class="text-center py-4" style="display: none;">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-primary">
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">Loading slots...</span>
                            </div>
                            <div id="noSlotsMessage" class="text-center py-4 text-sm text-muted-foreground"
                                style="display: none;">
                                No available slots for this date
                            </div>
                        </div>

                        @php
                            $durationPrices = $teacher->teacherSettings->pluck('value', 'key')->toArray();
                        @endphp
                        <h3 class="font-semibold text-foreground mb-3 text-lg">Lesson Duration</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ([30, 60, 90, 120] as $minutes)
                                @php
                                    $key = 'duration_' . $minutes;
                                    $price = $durationPrices[$key] ?? 'N/A';
                                    $label = $minutes === 120 ? '2 hours' : $minutes . ' minutes';
                                @endphp
                                <div>
                                    <input type="radio" name="duration" value="{{ $minutes }}"
                                        id="duration-{{ $minutes }}" class="peer hidden" required>
                                    <label for="duration-{{ $minutes }}"
                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-checked:border-primary cursor-pointer text-center">
                                        {{ $label }} <br>
                                        <span class="text-xs text-muted">$ {{ $price }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:col-span-5 border-t pt-8" style="opacity: 1;">
                        <h2 class="text-xl font-semibold text-foreground mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="mr-2 h-5 w-5 text-primary">
                                <path d="m7.5 4.27 9 5.15"></path>
                                <path
                                    d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z">
                                </path>
                                <path d="m3.3 7 8.7 5 8.7-5"></path>
                                <path d="M12 22V12"></path>
                            </svg>
                            ...Or Buy a Package &amp; Save!
                        </h2>

                        @php
                            $lessonPackages = $teacher->lessonPackages;
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($lessonPackages as $package)
                                <label for="package_{{ $package->id }}" class="block">
                                    <input type="radio" name="lesson_package" value="{{ $package->id }}"
                                        id="package_{{ $package->id }}" class="hidden peer">
                                    <div
                                        class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted peer-checked:border-primary/70 hover:border-primary/50">
                                        <h4 class="font-bold text-lg text-foreground">{{ $package->number_of_lessons }}
                                            Lessons</h4>
                                        @if ($package->number_of_lessons >= 20)
                                            <p class="text-sm font-semibold text-green-600">Save 20%</p>
                                        @elseif ($package->number_of_lessons >= 10)
                                            <p class="text-sm font-semibold text-green-600">Save 10%</p>
                                        @elseif ($package->number_of_lessons >= 5)
                                            <p class="text-sm font-semibold text-green-600">Save 5%</p>
                                        @endif
                                        <p class="text-2xl font-bold text-primary mt-2">
                                            ${{ number_format($package->price, 2) }}</p>
                                        <p class="text-xs text-muted-foreground">Just
                                            ${{ number_format($package->price / $package->number_of_lessons, 2) }} per
                                            lesson</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:col-span-5" style="opacity: 1;">
                        <div class="bg-primary/10 p-4 rounded-lg border border-primary/30 text-center">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Duration</p>
                                    <p class="text-lg font-medium text-foreground">
                                        <span id="selected-duration">—</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Time Slot</p>
                                    <p class="text-lg font-medium text-foreground">
                                        <span id="selected-time-slot">—</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Price</p>
                                    <p class="text-2xl font-bold text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="inline h-6 w-6 relative -top-0.5">
                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        <span id="selected-price">—</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5" style="opacity: 1;">
                        <form id="checkoutForm" action="{{ route('student.tutor.checkout') }}" method="GET">
                            <input type="hidden" name="type" id="checkoutType">
                            <input type="hidden" name="value" id="checkoutValue">
                            <input type="hidden" name="price" id="checkoutPrice">
                            <input type="hidden" name="slot_id" id="checkoutSlotId">
                            <input type="hidden" name="selected_date" id="checkoutSelectedDate">
                            <button type="submit" id="checkoutButton" disabled
                                class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 w-full btn-red text-lg py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-2 h-5 w-5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Confirm &amp; Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        const durationPrices = @json($durationPrices);
        const packagePrices = @json($teacher->lessonPackages->pluck('price', 'id'));

        const durationInputs = document.querySelectorAll('input[name="duration"]');
        const packageInputs = document.querySelectorAll('input[name="lesson_package"]');
        const selectedDurationElem = document.getElementById('selected-duration');
        const selectedPriceElem = document.getElementById('selected-price');
        const selectedTimeSlotElem = document.getElementById('selected-time-slot');

        const checkoutType = document.getElementById('checkoutType');
        const checkoutValue = document.getElementById('checkoutValue');
        const checkoutPrice = document.getElementById('checkoutPrice');
        const checkoutSlotId = document.getElementById('checkoutSlotId');
        const checkoutSelectedDate = document.getElementById('checkoutSelectedDate');
        const checkoutButton = document.getElementById('checkoutButton');

        let selectedSlotData = null;
        let selectedDateString = null;

        function updateCheckoutButton() {
            const hasSelection = (durationInputs[0].checked || durationInputs[1].checked || durationInputs[2].checked ||
                    durationInputs[3].checked) ||
                (packageInputs.length > 0 && [...packageInputs].some(p => p.checked));
            const hasTimeSlot = selectedSlotData !== null;

            // For individual lessons, both duration and time slot are required
            if ([...durationInputs].some(d => d.checked)) {
                checkoutButton.disabled = !hasTimeSlot;
            }
            // For packages, only selection is required (no specific time slot needed)
            else if ([...packageInputs].some(p => p.checked)) {
                checkoutButton.disabled = false;
            } else {
                checkoutButton.disabled = true;
            }
        }

        // Log all summary values to console
        function logSummaryValues() {
            const summary = {
                date: checkoutSelectedDate.value,
                slotTime: selectedTimeSlotElem.textContent,
                duration: selectedDurationElem.textContent,
                price: selectedPriceElem.textContent,
                checkoutType: checkoutType.value,
                checkoutValue: checkoutValue.value,
                slotId: checkoutSlotId.value
            };
            console.log('Booking Summary:', summary);
        }

        // Duration selection
        durationInputs.forEach(input => {
            input.addEventListener('change', function() {
                const minutes = this.value;
                const key = `duration_${minutes}`;
                const price = durationPrices[key];

                // Clear package selection
                packageInputs.forEach(pkg => pkg.checked = false);

                const label = minutes === '120' ? '2 hours' : minutes + ' minutes';
                selectedDurationElem.textContent = label;
                selectedPriceElem.textContent = price ? `${price}` : 'N/A';

                checkoutType.value = 'duration';
                checkoutValue.value = minutes;
                checkoutPrice.value = price ?? 0;

                logSummaryValues();

                updateCheckoutButton();
            });
        });

        // Package selection
        packageInputs.forEach(input => {
            input.addEventListener('change', function() {
                const packageId = this.value;
                const price = packagePrices[packageId];
                const label = this.closest('label')?.querySelector('h4')?.textContent.trim();

                // Clear duration selection
                durationInputs.forEach(dur => dur.checked = false);

                // Clear time slot for packages
                selectedSlotData = null;
                selectedTimeSlotElem.textContent = 'Not required for packages';
                checkoutSlotId.value = '';

                selectedDurationElem.textContent = label ?? '—';
                selectedPriceElem.textContent = price ? `${price}` : 'N/A';

                checkoutType.value = 'package';
                checkoutValue.value = packageId;
                checkoutPrice.value = price ?? 0;

                logSummaryValues();

                updateCheckoutButton();
            });
        });

        const teacherId = {{ $teacher->id }};
        console.log('Teacher ID:', teacherId);

        const API_BASE = '/public/availability';
        let currentDate = new Date();
        let selectedDate = new Date().getDate();
        let monthlyAvailabilities = {};
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (currentDate.getMonth() === (new Date()).getMonth() && currentDate.getFullYear() === (new Date())
            .getFullYear()) {
            selectedDate = (new Date()).getDate();
        }

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) return;

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
                if (alert) alert.remove();
            }, 5000);
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

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
                    ...options,
                    headers: {
                        ...defaultOptions.headers,
                        ...options.headers
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    let errorMessage = `HTTP error! status: ${response.status}`;
                    try {
                        const errorData = JSON.parse(errorText);
                        errorMessage = errorData.message || errorData.error || errorMessage;
                        if (errorData.errors) {
                            const validationErrors = Object.values(errorData.errors).flat();
                            errorMessage = validationErrors.join(', ');
                        }
                    } catch (_) {
                        errorMessage = errorText || errorMessage;
                    }
                    throw new Error(errorMessage);
                }

                return await response.json();
            } catch (error) {
                throw error;
            }
        }

        async function loadMonthlyAvailabilities(year, month) {
            try {
                const response = await apiRequest(`${API_BASE}/monthly/${teacherId}?year=${year}&month=${month}`);
                monthlyAvailabilities = response.availabilities || {};
            } catch (error) {
                console.error('Error loading monthly availabilities:', error);
                monthlyAvailabilities = {};
            }
        }

        async function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const currentMonthElement = document.getElementById('react-day-picker-49');
            if (currentMonthElement) {
                currentMonthElement.textContent = `${monthNames[month]} ${year}`;
            }

            await loadMonthlyAvailabilities(year, month + 1);

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const tbody = document.querySelector('tbody.rdp-tbody');
            if (!tbody) return;

            tbody.innerHTML = '';

            let dayCount = 1;

            for (let week = 0; week < 6; week++) {
                const tr = document.createElement('tr');
                tr.classList.add('flex', 'w-full', 'mt-2');

                for (let day = 0; day < 7; day++) {
                    const cellIndex = week * 7 + day;
                    const td = document.createElement('td');
                    td.classList.add('h-9', 'w-9', 'text-center', 'text-sm', 'p-0', 'relative');

                    const btn = document.createElement('button');
                    btn.setAttribute('class',
                        `rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 w-50`
                    );
                    btn.setAttribute('type', 'button');

                    if (cellIndex < firstDay) {
                        const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
                        btn.textContent = prevDate;
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'text-muted-foreground', 'day-outside');
                    } else if (dayCount <= daysInMonth) {
                        btn.textContent = dayCount;
                        const dateStr =
                            `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;

                        const hasAvailability = monthlyAvailabilities[dateStr] && monthlyAvailabilities[dateStr]
                            .length > 0;
                        const dateObj = new Date(year, month, dayCount);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        dateObj.setHours(0, 0, 0, 0);
                        const isPast = dateObj < today;

                        if (isPast) {
                            btn.disabled = true;
                            btn.classList.add('opacity-50', 'text-muted-foreground', 'day-outside');
                        } else if (hasAvailability) {
                            btn.classList.add('rdp-button_hover', 'hover:bg-accent', 'hover:text-accent-foreground');
                            btn.addEventListener('click', () => selectDate(dayCount, 0));
                        } else {
                            btn.disabled = true;
                            btn.classList.add('opacity-50');
                        }

                        if (dayCount === selectedDate) {
                            btn.classList.add('bg-warning', 'text-warning-foreground');
                        }
                        dayCount++;
                    } else {
                        const nextDate = dayCount - daysInMonth;
                        btn.textContent = nextDate;
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'text-muted-foreground', 'day-outside');
                        dayCount++;
                    }

                    td.appendChild(btn);
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);

                if (dayCount > daysInMonth && week >= 4) break;
            }

            updateSelectedDateText();
        }

        function updateSelectedDateText() {
            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate);
            const dayName = dayNames[selectedDateObj.getDay()];
            const monthName = monthNames[selectedDateObj.getMonth()];
            const dateDisplay = document.getElementById('selectedDateDisplay');

            if (dateDisplay) {
                dateDisplay.textContent = `${dayName}, ${monthName} ${selectedDate}`;
            }

            selectedDateString = formatDate(selectedDateObj);
            checkoutSelectedDate.value = selectedDateString;

            logSummaryValues();
        }

        async function displayTimeSlots() {
            const container = document.getElementById('availableSlotsContainer');
            if (!container) return;
            container.innerHTML = '';

            let loader = document.getElementById('slotsLoader');
            let noSlotsMessage = document.getElementById('noSlotsMessage');

            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'slotsLoader';
                loader.className = 'text-center py-4';
                loader.innerHTML =
                    `<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>`;
                container.parentNode.appendChild(loader);
            }

            if (!noSlotsMessage) {
                noSlotsMessage = document.createElement('p');
                noSlotsMessage.id = 'noSlotsMessage';
                noSlotsMessage.className = 'text-muted text-center py-4';
                noSlotsMessage.textContent = 'No available slots for this day.';
                noSlotsMessage.style.display = 'none';
                container.parentNode.appendChild(noSlotsMessage);
            }

            container.style.display = 'none';
            noSlotsMessage.style.display = 'none';
            loader.style.display = 'block';

            try {
                const dateStr = formatDate(new Date(currentDate.getFullYear(), currentDate.getMonth(), selectedDate));
                const response = await apiRequest(`${API_BASE}/date/${teacherId}?date=${dateStr}`);

                loader.style.display = 'none';

                const slots = response.slots || [];
                if (slots.length === 0) {
                    noSlotsMessage.style.display = 'block';
                    container.style.display = 'none';
                    selectedTimeSlotElem.textContent = 'Select a date to see available times';
                    logSummaryValues();
                    return;
                }
                noSlotsMessage.style.display = 'none';
                container.style.display = 'grid';

                slots.forEach(slot => {
                    if (!slot.is_available) return;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = slot.formatted_range;
                    btn.className =
                        'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border bg-background hover:text-accent-foreground h-10 px-4 w-full border-input hover:bg-accent cursor-pointer';
                    btn.addEventListener('click', () => selectSlot(slot));
                    container.appendChild(btn);
                });
            } catch (error) {
                loader.style.display = 'none';
                noSlotsMessage.textContent = 'Error loading slots: ' + error.message;
                noSlotsMessage.style.display = 'block';
                showAlert('Error loading time slots: ' + error.message, 'danger');
            }
        }

        function selectSlot(slot) {
            document.querySelectorAll('#availableSlotsContainer button.selected').forEach(btn =>
                btn.classList.remove('selected', 'bg-primary', 'text-white')
            );

            const buttons = Array.from(document.querySelectorAll('#availableSlotsContainer button'));
            const btn = buttons.find(b => b.textContent === slot.formatted_range);
            if (btn) btn.classList.add('selected', 'bg-primary', 'text-white');

            selectedTimeSlotElem.textContent = slot.formatted_range;

            checkoutType.value = 'slot';
            checkoutValue.value = slot.id;
            checkoutSlotId.value = slot.id;

            selectedSlotData = slot;

            logSummaryValues();

            updateCheckoutButton();
        }

        async function selectDate(date, monthOffset) {
            if (monthOffset !== 0) {
                if (monthOffset === -1) {
                    await previousMonth();
                } else {
                    await nextMonth();
                }
                return;
            }

            const selectedDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), date);
            const todayObj = new Date();
            todayObj.setHours(0, 0, 0, 0);
            selectedDateObj.setHours(0, 0, 0, 0);

            if (selectedDateObj < todayObj) {
                showAlert('Cannot select past dates. Please choose today or a future date.', 'warning');
                return;
            }

            selectedDate = date;
            await generateCalendar();
            await displayTimeSlots();
        }

        async function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            await generateCalendar();
            selectedDate = 1;
            await displayTimeSlots();
        }

        async function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            await generateCalendar();
            selectedDate = 1;
            await displayTimeSlots();
        }

        document.addEventListener('DOMContentLoaded', async () => {
            currentDate = new Date((new Date()).getFullYear(), (new Date()).getMonth(), 1);
            selectedDate = (new Date()).getDate();

            await generateCalendar();
            await displayTimeSlots();
        });
    </script>
@endsection
