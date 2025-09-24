@extends('website.master.master')
@section('title', 'Book-Tutor - FluentAll')
@section('content')
    <style>
        .calendar-container {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            max-width: 350px;
            width: 100%;
        }

        .calendar-header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 0.5rem 0 1rem 0;
        }

        .calendar-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .nav-button {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border: 1px solid #e5e7eb;
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            opacity: 0.5;
            transition: all 0.2s;
            color: #374151;
        }

        .nav-button:hover {
            opacity: 1;
            background-color: #f3f4f6;
        }

        .nav-button:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        .nav-button.prev {
            left: 4px;
        }

        .nav-button.next {
            right: 4px;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-header-row {
            display: flex;
        }

        .day-header {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: normal;
            text-align: center;
            width: 36px;
            padding: 0.5rem 0;
        }

        .calendar-row {
            display: flex;
            width: 100%;
            margin-top: 8px;
        }

        .day-cell {
            height: 36px;
            width: 36px;
            text-align: center;
            font-size: 0.875rem;
            padding: 0;
            position: relative;
        }

        .day-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: normal;
            transition: all 0.2s;
            color: #374151;
        }

        .day-button:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .day-button:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        .day-button.outside {
            color: #9ca3af;
            opacity: 0.5;
        }

        .day-button.disabled {
            color: #9ca3af;
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .day-button.selected {
            background-color: #3b82f6;
            color: white;
        }

        .day-button.selected:hover {
            background-color: #2563eb;
            color: white;
        }

        .day-button.today {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: 500;
        }

        .day-button.today.selected {
            background-color: #3b82f6;
            color: white;
        }

        /* Enhanced Time Slot Highlighting */
        .time-slot-btn {
            transition: all 0.3s ease;
            position: relative;
        }

        .time-slot-btn:hover {
            background-color: #dbeafe !important;
            border-color: #3b82f6 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.15);
        }

        .time-slot-btn.selected {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transform: translateY(-1px);
        }

        .time-slot-btn.selected:hover {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }

        /* Enhanced Duration Selection Highlighting */
        .duration-option {
            transition: all 0.3s ease;
        }

        .duration-option:hover {
            background-color: #f3f4f6;
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .duration-option input[type="radio"]:checked+label {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.25);
        }

        .duration-option input[type="radio"]:checked+label .text-xs {
            color: #e5e7eb !important;
        }

        .package-option {
            transition: all 0.3s ease;
        }

        .package-option:hover {
            background-color: #f8fafc;
            border-color: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .package-option input[type="radio"]:checked+div {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .package-option input[type="radio"]:checked+div h4,
        .package-option input[type="radio"]:checked+div p {
            color: white !important;
        }

        .package-option input[type="radio"]:checked+div .text-green-600 {
            color: #bbf7d0 !important;
        }

        .package-option input[type="radio"]:checked+div .text-muted-foreground {
            color: #e5e7eb !important;
        }

        /* Add a subtle animation for selections */
        @keyframes selectPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .time-slot-btn.selected,
        .duration-option input[type="radio"]:checked+label,
        .package-option input[type="radio"]:checked+div {
            animation: selectPulse 0.3s ease-out;
        }
    </style>
    <main class="flex-grow">
        <div class="min-h-screen bg-gray-50 py-8 md:py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <a href="{{ url()->previous() }}">
                    <button
                        class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border bg-background hover:text-accent-foreground h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary/10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 h-4 w-4">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        {{ __('welcome.key_73') }}
                    </button>
                </a>

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
                    <h1 class="text-3xl md:text-4xl font-bold text-foreground">{{ __('welcome.key_74') }} <span
                            class="text-gradient-yellow-red">{{ $teacher->name }}</span></h1>
                </div>
                <div id="alertContainer"></div>

                <div
                    class="bg-white p-6 md:p-8 rounded-xl shadow-xl border border-primary/20 grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-3">
                        <div class="calendar-container">
                            <div class="calendar-header">
                                <button class="nav-button prev" id="prevMonth">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </button>
                                <div class="calendar-title" id="calendarTitle">{{ __('welcome.key_76') }}</div>
                                <button class="nav-button next" id="nextMonth">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="calendar-grid">
                                <div class="calendar-header-row">
                                    <div class="day-header">{{ __('welcome.key_77') }}</div>
                                    <div class="day-header">{{ __('welcome.key_78') }}</div>
                                    <div class="day-header">{{ __('welcome.key_79') }}</div>
                                    <div class="day-header">{{ __('welcome.key_80') }}</div>
                                    <div class="day-header">{{ __('welcome.key_81') }}</div>
                                    <div class="day-header">{{ __('welcome.key_82') }}</div>
                                    <div class="day-header">{{ __('welcome.key_83') }}</div>
                                </div>
                                <div id="calendarBody"></div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-semibold text-foreground mb-3 text-lg">{{ __('welcome.key_211') }}</h3>
                            <div id="selectedDateDisplay" class="text-sm text-muted-foreground mb-3">
                                {{ __('welcome.key_212') }}
                            </div>
                            <div id="availableSlotsContainer" class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto pr-2">
                                <!-- Slots will be loaded dynamically here -->
                            </div>
                            <div id="slotsLoader" class="text-center py-4" style="display: none;">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-primary">
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">{{ __('welcome.key_213') }}</span>
                            </div>
                            <div id="noSlotsMessage" class="text-center py-4 text-sm text-muted-foreground"
                                style="display: none;">
                                {{ __('welcome.key_214') }}
                            </div>
                        </div>

                        {{-- @php
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
                                <div class="duration-option">
                                    <input type="radio" name="duration" value="{{ $minutes }}"
                                        id="duration-{{ $minutes }}" class="peer hidden" required>
                                    <label for="duration-{{ $minutes }}"
                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground cursor-pointer text-center transition-all duration-300">
                                        {{ $label }} <br>
                                        <span class="text-xs text-muted">$ {{ $price }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div> --}}
                        @php
                            $durationPrices = $teacher->teacherSettings->pluck('value', 'key')->toArray();
                            $price60 = $durationPrices['duration_60'] ?? 'N/A';
                        @endphp
                        <h3 class="font-semibold text-foreground mb-3 text-lg">{{ __('welcome.key_123') }}</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="duration-option">
                                <input type="radio" name="duration" value="60" id="duration-60"
                                    class="peer hidden" required checked>
                                <label for="duration-60"
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-primary bg-primary/10 p-4 text-center transition-all duration-300">
                                    {{ __('welcome.key_125') }} <br>
                                    <span class="text-xs text-muted">$ {{ $price60 }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="lg:col-span-5 border-t pt-8" style="opacity: 1;">
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
                                <label for="package_{{ $package->id }}" class="block package-option">
                                    <input type="radio" name="lesson_package" value="{{ $package->id }}"
                                        id="package_{{ $package->id }}" class="hidden peer">
                                    <div
                                        class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted hover:border-primary/50">
                                        <h4 class="font-bold text-lg text-foreground">{{ $package->number_of_lessons }}
                                            Lessons</h4>
                                        @if ($package->number_of_lessons >= 20)
                                            <p class="text-sm font-semibold text-green-600">Save 15%</p>
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
                    </div> --}}
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
                            {{ __('welcome.key_128') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if ($teacher->lessonPackages && $teacher->lessonPackages->count() > 0)
                                @foreach ($teacher->{{ __('welcome.key_215') }}
                                    <label for="package_{{ $package->id }}" class="block package-option">
                                        <input type="radio" name="lesson_package" value="{{ $package->id }}"
                                            id="package_{{ $package->id }}" class="hidden peer">
                                        <div
                                            class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted hover:border-primary/50">
                                            <h4 class="font-bold text-lg text-foreground">
                                                {{ $package->number_of_lessons }} Lessons</h4>

                                            @if ($package->discount_percentage > {{ __('welcome.key_154') }}
                                                <p class="text-sm font-semibold text-green-600">Save
                                                    {{ $package->discount_percentage }}%</p>
                                            @endif

                                            <div class="text-2xl font-bold text-primary mt-2">
                                                <span>${{ number_format($package->original_price ?? $package->price, 2) }}</span>
                                            </div>

                                            <p class="text-xs text-muted-foreground mt-1">
                                                Just
                                                ${{ number_format(($package->original_price ?? $package->price) / $package->number_of_lessons, 2) }}
                                                per lesson
                                            </p>

                                            @if ($package->{{ __('welcome.key_216') }}
                                                <p class="text-xs text-muted-foreground">
                                                    {{ $package->duration_per_lesson }} minutes per lesson
                                                </p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            @else
                                <div class="col-span-3 text-center py-8">
                                    <p class="text-muted-foreground">{{ __('welcome.key_217') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="lg:col-span-5" style="opacity: 1;">
                        <div class="bg-primary/10 p-4 rounded-lg border border-primary/30 text-center">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">{{ __('welcome.key_218') }}</p>
                                    <p class="text-lg font-medium text-foreground">
                                        <span id="selected-duration">—</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">{{ __('welcome.key_219') }}</p>
                                    <p class="text-lg font-medium text-foreground">
                                        <span id="selected-time-slot">—</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">{{ __('welcome.key_220') }}</p>
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

                    @if (!($teacher->bookingRules->{{ __('welcome.key_221') }}
                        <div class="lg:col-span-5 text-center">
                            <p class="text-sm text-red-500">{{ __('welcome.key_222') }}</p>
                        </div>
                    @else
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
                                    {{ __('welcome.key_143') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script>
        class BookingCalendar {
            constructor(teacherId) {
                this.teacherId = teacherId;
                this.currentDate = new Date();
                this.selectedDate = null;
                this.selectedSlot = null;
                this.today = new Date();
                this.availabilities = {};
                this.currentSlots = [];

                this.monthNames = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];

                this.init();
            }

            init() {
                this.bindEvents();
                this.loadMonthlyAvailability().then(() => {
                    this.render();
                });
            }

            bindEvents() {
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.previousMonth();
                });

                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.nextMonth();
                });

                document.addEventListener('click', (e) => {
                    if (e.target.classList.contains('time-slot-btn')) {
                        this.selectTimeSlot(e.target);
                    }
                });
            }

            async previousMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                await this.loadMonthlyAvailability();
                this.render();
                this.clearSlots();
            }

            async nextMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                await this.loadMonthlyAvailability();
                this.render();
                this.clearSlots();
            }

            async selectDate(date) {
                this.selectedDate = new Date(date);
                this.selectedSlot = null;
                this.render();
                await this.loadDateAvailability(date);
                this.updateCheckoutButton();
            }

            selectTimeSlot(slotElement) {
                document.querySelectorAll('.time-slot-btn').forEach(btn => {
                    btn.classList.remove('selected');
                });

                slotElement.classList.add('selected');

                this.selectedSlot = {
                    id: slotElement.dataset.slotId,
                    time: slotElement.textContent.trim(),
                    date: this.formatDate(this.selectedDate)
                };

                this.updateSelectedTimeDisplay();
                this.updateCheckoutButton();
            }

            async loadMonthlyAvailability() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth() + 1;

                try {
                    const response = await fetch(
                        `/public/availability/monthly/${this.teacherId}?year=${year}&month=${month}`);
                    const data = await response.json();
                    this.availabilities = data.availabilities || {};
                } catch (error) {
                    console.error('Error fetching monthly availability:', error);
                    this.showAlert('Error loading calendar data', 'error');
                    this.availabilities = {};
                }
            }

            async loadDateAvailability(date) {
                const formattedDate = this.formatDate(date);

                this.showSlotsLoader(true);

                try {
                    const response = await fetch(`/public/availability/date/${this.teacherId}?date=${formattedDate}`);
                    const data = await response.json();
                    this.currentSlots = data.slots || [];

                    this.renderTimeSlots();
                    this.updateSelectedDateDisplay(formattedDate);
                } catch (error) {
                    console.error('Error fetching date availability:', error);
                    this.showAlert('Error loading time slots', 'error');
                    this.currentSlots = [];
                    this.renderTimeSlots();
                } finally {
                    this.showSlotsLoader(false);
                }
            }

            formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            formatDateDisplay(date) {
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            }

            isToday(date) {
                return date.toDateString() === this.today.toDateString();
            }

            isSelected(date) {
                return this.selectedDate && date.toDateString() === this.selectedDate.toDateString();
            }

            isSameMonth(date) {
                return date.getMonth() === this.currentDate.getMonth() &&
                    date.getFullYear() === this.currentDate.getFullYear();
            }

            hasAvailability(date) {
                const formattedDate = this.formatDate(date);
                return this.availabilities[formattedDate] && this.availabilities[formattedDate].length > 0;
            }

            isDateDisabled(date) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return date < today || !this.hasAvailability(date);
            }

            render() {
                this.renderHeader();
                this.renderDays();
            }

            renderHeader() {
                const title = document.getElementById('calendarTitle');
                title.textContent = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
            }

            renderDays() {
                const calendarBody = document.getElementById('calendarBody');
                calendarBody.innerHTML = '';

                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                const firstDay = new Date(year, month, 1);
                const startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());

                const totalDays = 42;
                let currentWeekRow = null;

                for (let i = 0; i < totalDays; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);

                    if (i % 7 === 0) {
                        currentWeekRow = document.createElement('div');
                        currentWeekRow.className = 'calendar-row';
                        calendarBody.appendChild(currentWeekRow);
                    }

                    const dayCell = document.createElement('div');
                    dayCell.className = 'day-cell';

                    const dayButton = document.createElement('button');
                    dayButton.className = 'day-button';
                    dayButton.textContent = date.getDate();
                    dayButton.type = 'button';
                    if (!this.isSameMonth(date)) {
                        dayButton.classList.add('outside');
                    }

                    if (this.isToday(date)) {
                        dayButton.classList.add('today');
                    }

                    if (this.isSelected(date)) {
                        dayButton.classList.add('selected');
                        dayButton.setAttribute('aria-selected', 'true');
                    }

                    if (this.isDateDisabled(date)) {
                        dayButton.classList.add('disabled');
                        dayButton.disabled = true;
                    } else if (this.hasAvailability(date)) {
                        dayButton.classList.add('has-availability');
                        const indicator = document.createElement('div');
                        indicator.className = 'availability-indicator';
                        dayButton.appendChild(indicator);
                    }
                    if (!this.isDateDisabled(date)) {
                        dayButton.addEventListener('click', () => {
                            this.selectDate(date);
                        });
                    }

                    dayButton.setAttribute('role', 'gridcell');
                    dayButton.setAttribute('tabindex', this.isSelected(date) ? '0' : '-1');

                    dayCell.appendChild(dayButton);
                    currentWeekRow.appendChild(dayCell);
                }
            }

            renderTimeSlots() {
                const container = document.getElementById('availableSlotsContainer');
                const noSlotsMessage = document.getElementById('noSlotsMessage');

                container.innerHTML = '';

                if (this.currentSlots.length === 0) {
                    container.style.display = 'none';
                    noSlotsMessage.style.display = 'block';
                    return;
                }

                container.style.display = 'grid';
                noSlotsMessage.style.display = 'none';

                this.currentSlots.forEach(slot => {
                    const slotButton = document.createElement('button');
                    slotButton.className =
                        'time-slot-btn px-3 py-2 text-sm border border-gray-300 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500';
                    slotButton.textContent = slot.formatted_range;
                    slotButton.dataset.slotId = slot.id;
                    slotButton.type = 'button';

                    container.appendChild(slotButton);
                });
            }

            updateSelectedDateDisplay(date) {
                const display = document.getElementById('selectedDateDisplay');
                if (date) {
                    const dateObj = new Date(date + 'T00:00:00'); // Ensure proper date parsing
                    display.textContent = `Available slots for ${this.formatDateDisplay(dateObj)}`;
                } else {
                    display.textContent = 'Select a date to see available times';
                }
            }

            updateSelectedTimeDisplay() {
                const timeSlotDisplay = document.getElementById('selected-time-slot');
                if (this.selectedSlot) {
                    timeSlotDisplay.textContent =
                        `${this.formatDateDisplay(this.selectedDate)} at ${this.selectedSlot.time}`;
                } else {
                    timeSlotDisplay.textContent = '—';
                }
            }

            updateCheckoutButton() {
                const checkoutButton = document.getElementById('checkoutButton');
                const slotIdInput = document.getElementById('checkoutSlotId');
                const selectedDateInput = document.getElementById('checkoutSelectedDate');
                const durationSelected = document.querySelector('input[name="duration"]:checked');
                const packageSelected = document.querySelector('input[name="lesson_package"]:checked');

                let canCheckout = false;

                if (packageSelected) {
                    canCheckout = true;
                    slotIdInput.value = '';
                    selectedDateInput.value = '';
                } else if (durationSelected && this.selectedDate && this.selectedSlot) {
                    canCheckout = true;
                    slotIdInput.value = this.selectedSlot.id;
                    selectedDateInput.value = this.selectedSlot.date;
                } else {
                    canCheckout = false;
                    slotIdInput.value = '';
                    selectedDateInput.value = '';
                }
                checkoutButton.disabled = !canCheckout;
            }

            showSlotsLoader(show) {
                const loader = document.getElementById('slotsLoader');
                const container = document.getElementById('availableSlotsContainer');
                const noSlotsMessage = document.getElementById('noSlotsMessage');

                if (show) {
                    loader.style.display = 'block';
                    container.style.display = 'none';
                    noSlotsMessage.style.display = 'none';
                } else {
                    loader.style.display = 'none';
                }
            }

            clearSlots() {
                this.currentSlots = [];
                this.selectedSlot = null;
                this.renderTimeSlots();
                this.updateSelectedDateDisplay(null);
                this.updateSelectedTimeDisplay();
                this.updateCheckoutButton();
            }

            showAlert(message, type = 'info') {
                const alertContainer = document.getElementById('alertContainer');
                const alertDiv = document.createElement('div');

                const alertClass = type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
                    'bg-blue-100 border-blue-400 text-blue-700';

                alertDiv.className = `border px-4 py-3 rounded mb-4 ${alertClass}`;
                alertDiv.textContent = message;

                alertContainer.appendChild(alertDiv);
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const checkoutForm = document.getElementById('checkoutForm');

            // Enhanced form submission handler
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const params = new URLSearchParams();

                    // Add all form data to params
                    for (let [key, value] of formData.entries()) {
                        params.append(key, value);
                    }

                    // Add teacher ID to ensure it's always included
                    if (!params.has('teacher_id')) {
                        params.append('teacher_id', {{ $teacher->id }});
                    }

                    // Construct URL with all parameters
                    const url = `{{ route('student.tutor.checkout') }}?${params.toString()}`;

                    // Redirect to checkout
                    window.location.href = url;
                });
            }

            // Rest of your existing calendar and selection logic...
            const teacherId = {{ $teacher->id }};
            const calendar = new BookingCalendar(teacherId);
            const durationPrices = @json($durationPrices);

            const packagePrices = {};
            const packageDetails = {};

            @foreach ($teacher->lessonPackages as $package)
                packagePrices[{{ $package->id }}] = {{ $package->original_price ?? $package->price }};
                packageDetails[{{ $package->id }}] = {
                    original_price: {{ $package->original_price ?? $package->price }},
                    discount_percent: {{ $package->discount_percentage ?? 0 }},
                    lessons: {{ $package->number_of_lessons }},
                    duration_per_lesson: {{ $package->duration_per_lesson ?? 60 }},
                    name: "{{ $package->name ?? $package->number_of_lessons . ' Lessons' }}"
                };
            @endforeach

            const durationInputs = document.querySelectorAll('input[name="duration"]');
            const packageInputs = document.querySelectorAll('input[name="lesson_package"]');
            const selectedDurationElem = document.getElementById('selected-duration');
            const selectedPriceElem = document.getElementById('selected-price');

            const checkoutType = document.getElementById('checkoutType');
            const checkoutValue = document.getElementById('checkoutValue');
            const checkoutPrice = document.getElementById('checkoutPrice');


            // Enhanced package selection handler
            packageInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const packageId = this.value;
                    const packageInfo = packageDetails[packageId];

                    if (packageInfo) {
                        // Clear duration selection
                        durationInputs.forEach(dur => dur.checked = false);

                        // Update display with original price
                        selectedDurationElem.textContent = packageInfo.name;
                        selectedPriceElem.textContent = packageInfo.original_price.toFixed(2);

                        // Set checkout form values with original price
                        checkoutType.value = 'package';
                        checkoutValue.value = packageId;
                        checkoutPrice.value = packageInfo.original_price;

                        // Remove existing package inputs
                        document.querySelectorAll(
                                '#checkoutForm input[name^="package_"], #checkoutForm input[name="original_price"], #checkoutForm input[name="discount_percent"]'
                            )
                            .forEach(input => input.remove());

                        // Add package-specific hidden inputs
                        const form = document.getElementById('checkoutForm');
                        const hiddenInputs = [{
                                name: 'package_lessons',
                                value: packageInfo.lessons
                            },
                            {
                                name: 'original_price',
                                value: packageInfo.original_price
                            },
                            {
                                name: 'discount_percent',
                                value: packageInfo.discount_percent
                            },
                            {
                                name: 'package_duration_per_lesson',
                                value: packageInfo.duration_per_lesson
                            }
                        ];

                        hiddenInputs.forEach(inputData => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = inputData.name;
                            input.value = inputData.value;
                            input.className = 'dynamic-checkout-input'; // For easy cleanup
                            form.appendChild(input);
                        });

                        calendar.updateSelectedTimeDisplay();
                        calendar.updateCheckoutButton();
                    }
                });
            });

            // Enhanced duration selection handler
            // durationInputs.forEach(input => {
            //     input.addEventListener('change', function() {
            //         const minutes = this.value;
            //         const key = `duration_${minutes}`;
            //         const price = durationPrices[key];

            //         // Clear package selection
            //         packageInputs.forEach(pkg => pkg.checked = false);

            //         // Remove package-specific hidden inputs
            //         document.querySelectorAll('#checkoutForm .dynamic-checkout-input')
            //             .forEach(input => input.remove());

            //         const label = minutes === '120' ? '2 hours' : minutes + ' minutes';
            //         selectedDurationElem.textContent = label;
            //         selectedPriceElem.textContent = price ? `${price}` : 'N/A';

            //         checkoutType.value = 'duration';
            //         checkoutValue.value = minutes;
            //         checkoutPrice.value = price ?? 0;

            //         calendar.updateSelectedTimeDisplay();
            //         calendar.updateCheckoutButton();
            //     });
            // });
            // Simplified duration selection handler for 60 minutes only
            const duration60Input = document.getElementById('duration-60');
            if (duration60Input) {
                duration60Input.addEventListener('change', function() {
                    const minutes = '60';
                    const price = durationPrices['duration_60'];

                    // Clear package selection
                    packageInputs.forEach(pkg => pkg.checked = false);

                    // Remove package-specific hidden inputs
                    document.querySelectorAll('#checkoutForm .dynamic-checkout-input')
                        .forEach(input => input.remove());

                    selectedDurationElem.textContent = '60 minutes';
                    selectedPriceElem.textContent = price ? `${price}` : 'N/A';

                    checkoutType.value = 'duration';
                    checkoutValue.value = minutes;
                    checkoutPrice.value = price ?? 0;

                    calendar.updateSelectedTimeDisplay();
                    calendar.updateCheckoutButton();
                });

                // Auto-trigger the change event to set initial values
                if (duration60Input.checked) {
                    duration60Input.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
@endsection
