@extends('teacher.master.master')
@section('title', 'Public Profile - FluentAll')
@section('content')
    <style>
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .btn-red {
            background-color: #dc2626;
        }

        .btn-red:hover {
            background-color: #b91c1c;
        }

        .tab-active {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .tab-inactive {
            background-color: transparent;
            color: #64748b;
        }

        .tab-inactive:hover {
            background-color: #f8fafc;
            color: #475569;
        }
    </style>
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto max-w-6xl">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-3 h-8 w-8 text-blue-600">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                            </path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Lesson Settings
                    </h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Tab Navigation -->
                    <div class="md:col-span-1" style="opacity: 1; transform: none;">
                        <nav class="space-y-2">
                            <button id="lesson-management-tab" onclick="switchTab('lesson-management')"
                                class="tab-button tab-active inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 w-full justify-start">
                                Lesson Management
                            </button>
                            <button id="booking-rules-tab" onclick="switchTab('booking-rules')"
                                class="tab-button tab-inactive inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 w-full justify-start">
                                Booking Rules
                            </button>
                        </nav>
                    </div>

                    <!-- Lesson Management Tab Content -->
                    <div id="lesson-management-content" class="md:col-span-3 tab-content"
                        style="opacity: 1; transform: none;">
                        <div class="space-y-6">
                            <!-- Individual Lesson Pricing -->
                            <div class="rounded-lg border bg-white text-gray-900 shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-6">
                                    <h3 class="text-2xl font-semibold leading-none tracking-tight flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="mr-2 h-5 w-5 text-blue-600">
                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Individual Lesson Pricing
                                    </h3>
                                    <p class="text-sm text-gray-600">Set the price for each lesson duration.</p>
                                </div>
                                <div class="p-6 pt-0 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-3">
                                        <label class="text-sm font-medium leading-none w-24" for="price-30">30 mins</label>
                                        <div class="relative flex-grow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                                                <line x1="12" x2="12" y1="2" y2="22"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            <input type="number"
                                                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="price-30" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <label class="text-sm font-medium leading-none w-24" for="price-60">60 mins</label>
                                        <div class="relative flex-grow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                                                <line x1="12" x2="12" y1="2" y2="22"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            <input type="number"
                                                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="price-60" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <label class="text-sm font-medium leading-none w-24" for="price-90">90 mins</label>
                                        <div class="relative flex-grow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                                                <line x1="12" x2="12" y1="2" y2="22">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            <input type="number"
                                                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="price-90" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <label class="text-sm font-medium leading-none w-24" for="price-120">120
                                            mins</label>
                                        <div class="relative flex-grow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                                                <line x1="12" x2="12" y1="2" y2="22">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            <input type="number"
                                                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="price-120" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lesson Packages -->
                            <div class="rounded-lg border bg-white text-gray-900 shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-6">
                                    <h3 class="text-2xl font-semibold leading-none tracking-tight flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="mr-2 h-5 w-5 text-blue-600">
                                            <path d="m7.5 4.27 9 5.15"></path>
                                            <path
                                                d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z">
                                            </path>
                                            <path d="m3.3 7 8.7 5 8.7-5"></path>
                                            <path d="M12 22V12"></path>
                                        </svg>
                                        Lesson Packages
                                    </h3>
                                    <p class="text-sm text-gray-600">Create bundles of lessons to offer a discount.</p>
                                </div>
                                <div class="p-6 pt-0 space-y-4">
                                    <!-- Package 1 -->
                                    <div class="border p-4 rounded-lg space-y-3 bg-gray-50/50">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold">Package 1</p>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="sr-only peer" checked>
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <label class="text-sm font-medium">Number of Lessons</label>
                                                <select
                                                    class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="5">5 Lessons</option>
                                                    <option value="10">10 Lessons</option>
                                                    <option value="15">15 Lessons</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium">Duration per Lesson</label>
                                                <select
                                                    class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="30">30 mins</option>
                                                    <option value="60" selected>60 mins</option>
                                                    <option value="90">90 mins</option>
                                                    <option value="120">120 mins</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium">Total Price</label>
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                                                        <line x1="12" x2="12" y1="2"
                                                            y2="22"></line>
                                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                    </svg>
                                                    <input type="number"
                                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Days</label>
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3 capitalize"
                                                    type="button" disabled>mon</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3 capitalize btn-red"
                                                    type="button">tue</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3 capitalize"
                                                    type="button" disabled>wed</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3 capitalize btn-red"
                                                    type="button">thu</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3 capitalize"
                                                    type="button" disabled>fri</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3 capitalize"
                                                    type="button" disabled>sat</button>
                                                <button
                                                    class="inline-flex items-center justify-center text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3 capitalize"
                                                    type="button" disabled>sun</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 border border-gray-300 bg-white hover:bg-gray-50 h-10 px-4 py-2 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 12h8"></path>
                                            <path d="M12 8v8"></path>
                                        </svg>
                                        Add New Group Class
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 text-right">
                            <button
                                class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 bg-red-600 text-white hover:bg-red-700 h-10 btn-red text-lg px-8 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Save All Changes
                            </button>
                        </div>
                    </div>

                    <!-- Booking Rules Tab Content -->
                    <div id="booking-rules-content" class="md:col-span-3 tab-content hidden"
                        style="opacity: 1; transform: none;">
                        <div class="rounded-lg border bg-white text-gray-900 shadow-sm">
                            <div class="flex flex-col space-y-1.5 p-6">
                                <h3 class="text-2xl font-semibold leading-none tracking-tight">Booking Rules</h3>
                                <p class="text-sm text-gray-600">Define how and when students can book lessons with you.
                                </p>
                            </div>
                            <div class="p-6 pt-0 space-y-6">
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4">
                                    <label class="text-sm font-medium leading-none mb-2 sm:mb-0">
                                        <p class="font-semibold flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-2 h-4 w-4 text-blue-600">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            Minimum booking notice
                                        </p>
                                        <p class="text-xs text-gray-600">How much advance notice you need for a new
                                            booking.</p>
                                    </label>
                                    <select
                                        class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-[180px]">
                                        <option value="1">1 hour</option>
                                        <option value="2">2 hours</option>
                                        <option value="6">6 hours</option>
                                        <option value="12">12 hours</option>
                                        <option value="24" selected>24 hours</option>
                                        <option value="48">48 hours</option>
                                        <option value="72">72 hours</option>
                                    </select>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4">
                                    <label class="text-sm font-medium leading-none">
                                        <p class="font-semibold flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-2 h-4 w-4 text-blue-600">
                                                <rect width="18" height="18" x="3" y="4" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" x2="16" y1="2" y2="6">
                                                </line>
                                                <line x1="8" x2="8" y1="2" y2="6">
                                                </line>
                                                <line x1="3" x2="21" y1="10" y2="10">
                                                </line>
                                            </svg>
                                            Booking window
                                        </p>
                                        <p class="text-xs text-gray-600">How far in the future students can book.</p>
                                    </label>
                                    <select
                                        class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-[180px]">
                                        <option value="7">7 days</option>
                                        <option value="14">14 days</option>
                                        <option value="30" selected>30 days</option>
                                        <option value="60">60 days</option>
                                        <option value="90">90 days</option>
                                    </select>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4">
                                    <label class="text-sm font-medium leading-none">
                                        <p class="font-semibold flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-2 h-4 w-4 text-blue-600">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            Automatic break after lesson
                                        </p>
                                        <p class="text-xs text-gray-600">Set a buffer time after each lesson.</p>
                                    </label>
                                    <select
                                        class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-[180px]">
                                        <option value="0">No break</option>
                                        <option value="5">5 minutes</option>
                                        <option value="10">10 minutes</option>
                                        <option value="15" selected>15 minutes</option>
                                        <option value="30">30 minutes</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    <label class="text-sm font-medium leading-none">
                                        <p class="font-semibold flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-2 h-4 w-4 text-blue-600">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <polyline points="16 11 18 13 22 9"></polyline>
                                            </svg>
                                            Accepting new students
                                        </p>
                                        <p class="text-xs text-gray-600">Toggle if you are open to new student bookings.
                                        </p>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 text-right">
                            <button
                                class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 bg-red-600 text-white hover:bg-red-700 h-10 btn-red text-lg px-8 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('tab-active');
                button.classList.add('tab-inactive');
            });

            // Show selected tab content
            const selectedContent = document.getElementById(tabName + '-content');
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Add active class to selected tab button
            const selectedButton = document.getElementById(tabName + '-tab');
            if (selectedButton) {
                selectedButton.classList.remove('tab-inactive');
                selectedButton.classList.add('tab-active');
            }
        }

        // Initialize tab functionality on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set the first tab as active by default
            switchTab('lesson-management');
        });
    </script>
@endsection
