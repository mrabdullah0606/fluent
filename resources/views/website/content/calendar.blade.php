@extends('website.master.master')
@section('title', 'Calendar - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="min-h-screen bg-gray-50 py-8 md:py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <div style="opacity: 1; transform: none;">
                    <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary/10"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 h-4 w-4">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg> Back
                    </button>
                    <div class="text-center mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="h-16 w-16 mx-auto text-primary mb-3">
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
                                class="text-gradient-yellow-red">Sarah Williams</span>
                        </h1>
                    </div>
                    <div
                        class="bg-white p-6 md:p-8 rounded-xl shadow-xl border border-primary/20 grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="rdp rounded-md border border-input bg-background shadow-sm p-4 w-full">
                                <div class="flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0">
                                    <div class="space-y-4 rdp-caption_start rdp-caption_end">
                                        <div class="flex justify-center pt-1 relative items-center">
                                            <div class="text-sm font-medium" aria-live="polite" role="presentation"
                                                id="react-day-picker-4">July 2025</div>
                                            <div class="space-x-1 flex items-center"><button name="previous-month"
                                                    aria-label="Go to previous month"
                                                    class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute left-1"
                                                    type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="h-4 w-4">
                                                        <path d="m15 18-6-6 6-6"></path>
                                                    </svg></button><button name="next-month" aria-label="Go to next month"
                                                    class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute right-1"
                                                    type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="h-4 w-4">
                                                        <path d="m9 18 6-6-6-6"></path>
                                                    </svg></button></div>
                                        </div>
                                        <table class="w-full border-collapse space-y-1" role="grid"
                                            aria-labelledby="react-day-picker-4">
                                            <thead class="rdp-head">
                                                <tr class="flex">
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Sunday">Su</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Monday">Mo</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Tuesday">Tu</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Wednesday">We</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Thursday">Th</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Friday">Fr</th>
                                                    <th scope="col"
                                                        class="text-muted-foreground rounded-md w-9 font-normal text-[0.8rem]"
                                                        aria-label="Saturday">Sa</th>
                                                </tr>
                                            </thead>
                                            <tbody class="rdp-tbody">
                                                <tr class="flex w-full mt-2">
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50 day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">29</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50 day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">30</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">1</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">2</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">3</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">4</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">5</button></td>
                                                </tr>
                                                <tr class="flex w-full mt-2">
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">6</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">7</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">8</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">9</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">10</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">11</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">12</button></td>
                                                </tr>
                                                <tr class="flex w-full mt-2">
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">13</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">14</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">15</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">16</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">17</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">18</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">19</button></td>
                                                </tr>
                                                <tr class="flex w-full mt-2">
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">20</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">21</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">22</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">23</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">24</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">25</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">26</button></td>
                                                </tr>
                                                <tr class="flex w-full mt-2">
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">27</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 text-muted-foreground opacity-50"
                                                            role="gridcell" disabled="" tabindex="-1"
                                                            type="button">28</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground bg-accent text-accent-foreground"
                                                            role="gridcell" aria-selected="true" tabindex="0"
                                                            type="button">29</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100"
                                                            role="gridcell" tabindex="-1" type="button">30</button>
                                                    </td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100"
                                                            role="gridcell" tabindex="-1" type="button">31</button>
                                                    </td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                            role="gridcell" tabindex="-1" type="button">1</button></td>
                                                    <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                        role="presentation"><button name="day"
                                                            class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9 p-0 font-normal aria-selected:opacity-100 day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                            role="gridcell" tabindex="-1" type="button">2</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <h3 class="font-semibold text-foreground mb-3 text-lg">Available Slots for <span
                                        class="text-primary">July 29th, 2025</span></h3>
                                <div class="grid grid-cols-3 gap-3 max-h-60 overflow-y-auto pr-2"><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">09:00</button><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">10:00</button><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">11:00</button><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">14:00</button><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">15:00</button><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full border-input hover:bg-accent">16:00</button>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground mb-3 text-lg">Lesson Duration</h3>
                                <div role="radiogroup" aria-required="false" dir="ltr"
                                    class="grid grid-cols-2 gap-4" tabindex="0" style="outline: none;">
                                    <div><button type="button" role="radio" aria-checked="false"
                                            data-state="unchecked" value="30"
                                            class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 peer sr-only"
                                            id="duration-30" tabindex="-1" data-radix-collection-item=""></button><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&amp;:has([data-state=checked])]:border-primary cursor-pointer text-center"
                                            for="duration-30">30 minutes</label></div>
                                    <div><button type="button" role="radio" aria-checked="true" data-state="checked"
                                            value="60"
                                            class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 peer sr-only"
                                            id="duration-60" tabindex="-1" data-radix-collection-item=""><span
                                                data-state="checked" class="flex items-center justify-center"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="h-2.5 w-2.5 fill-current text-current">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                </svg></span></button><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&amp;:has([data-state=checked])]:border-primary cursor-pointer text-center"
                                            for="duration-60">60 minutes</label></div>
                                    <div><button type="button" role="radio" aria-checked="false"
                                            data-state="unchecked" value="90"
                                            class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 peer sr-only"
                                            id="duration-90" tabindex="-1" data-radix-collection-item=""></button><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&amp;:has([data-state=checked])]:border-primary cursor-pointer text-center"
                                            for="duration-90">90 minutes</label></div>
                                    <div><button type="button" role="radio" aria-checked="false"
                                            data-state="unchecked" value="120"
                                            class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 peer sr-only"
                                            id="duration-120" tabindex="-1"
                                            data-radix-collection-item=""></button><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&amp;:has([data-state=checked])]:border-primary cursor-pointer text-center"
                                            for="duration-120">2 hours</label></div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-5 border-t pt-8" style="opacity: 1;">
                            <h2 class="text-xl font-semibold text-foreground mb-4 flex items-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <path d="m7.5 4.27 9 5.15"></path>
                                    <path
                                        d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z">
                                    </path>
                                    <path d="m3.3 7 8.7 5 8.7-5"></path>
                                    <path d="M12 22V12"></path>
                                </svg>...Or Buy a Package &amp; Save!</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div
                                    class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted hover:border-primary/50">
                                    <h4 class="font-bold text-lg text-foreground">5 Lessons</h4>
                                    <p class="text-sm font-semibold text-green-600">Save 5%</p>
                                    <p class="text-2xl font-bold text-primary mt-2">$133.00</p>
                                    <p class="text-xs text-muted-foreground">Just $26.60 per lesson</p>
                                </div>
                                <div
                                    class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted hover:border-primary/50">
                                    <h4 class="font-bold text-lg text-foreground">10 Lessons</h4>
                                    <p class="text-sm font-semibold text-green-600">Save 10%</p>
                                    <p class="text-2xl font-bold text-primary mt-2">$252.00</p>
                                    <p class="text-xs text-muted-foreground">Just $25.20 per lesson</p>
                                </div>
                                <div
                                    class="p-4 border-2 rounded-lg cursor-pointer transition-all text-center border-muted hover:border-primary/50">
                                    <h4 class="font-bold text-lg text-foreground">20 Lessons</h4>
                                    <p class="text-sm font-semibold text-green-600">Save 20%</p>
                                    <p class="text-2xl font-bold text-primary mt-2">$448.00</p>
                                    <p class="text-xs text-muted-foreground">Just $22.40 per lesson</p>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-5" style="opacity: 1;">
                            <div class="bg-primary/10 p-4 rounded-lg border border-primary/30 text-center">
                                <p class="text-lg font-medium text-foreground">60 min Lesson</p>
                                <p class="text-2xl font-bold text-primary mt-1"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline h-6 w-6 relative -top-0.5">
                                        <line x1="12" x2="12" y1="2" y2="22"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>28.00</p>
                            </div>
                        </div>
                        <div class="lg:col-span-5" style="opacity: 1;"><button
                                class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 w-full btn-red text-lg py-3"
                                disabled=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> Confirm &amp; Proceed to Checkout</button></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
