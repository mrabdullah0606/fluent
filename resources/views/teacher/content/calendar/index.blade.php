@extends('teacher.master.master')
@section('title', 'Calendar - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8 flex items-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-3 h-8 w-8 text-primary">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                            <line x1="16" x2="16" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="2" y2="6"></line>
                            <line x1="3" x2="21" y1="10" y2="10"></line>
                        </svg> My Calendar &amp; Availability</h1>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <div class="lg:col-span-2" style="opacity: 1; transform: none;">
                        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                            <div class="p-2 md:p-4">
                                <div class="rdp p-3 rounded-md w-full">
                                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0">
                                        <div class="space-y-4 rdp-caption_start rdp-caption_end">
                                            <div class="flex justify-center pt-1 relative items-center">
                                                <div class="text-lg" aria-live="polite" role="presentation"
                                                    id="react-day-picker-33">July 2025</div>
                                                <div class="space-x-1 flex items-center"><button name="previous-month"
                                                        aria-label="Go to previous month"
                                                        class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute left-1"
                                                        type="button"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="h-4 w-4">
                                                            <path d="m15 18-6-6 6-6"></path>
                                                        </svg></button><button name="next-month"
                                                        aria-label="Go to next month"
                                                        class="rdp-button_reset rdp-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100 absolute right-1"
                                                        type="button"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="h-4 w-4">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg></button></div>
                                            </div>
                                            <table class="w-full border-collapse space-y-1" role="grid"
                                                aria-labelledby="react-day-picker-33">
                                                <thead class="rdp-head">
                                                    <tr class="flex">
                                                        <th scope="col" class="text-base" aria-label="Sunday">Su</th>
                                                        <th scope="col" class="text-base" aria-label="Monday">Mo</th>
                                                        <th scope="col" class="text-base" aria-label="Tuesday">Tu</th>
                                                        <th scope="col" class="text-base" aria-label="Wednesday">We</th>
                                                        <th scope="col" class="text-base" aria-label="Thursday">Th</th>
                                                        <th scope="col" class="text-base" aria-label="Friday">Fr</th>
                                                        <th scope="col" class="text-base" aria-label="Saturday">Sa</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="rdp-tbody">
                                                    <tr class="flex w-full mt-2">
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                                role="gridcell" tabindex="-1" type="button">29</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                                role="gridcell" tabindex="-1" type="button">30</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">1</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">2</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">3</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">4</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">5</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="flex w-full mt-2">
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">6</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">7</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">8</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">9</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">10</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">11</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">12</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="flex w-full mt-2">
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">13</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">14</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">15</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">16</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">17</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">18</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">19</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="flex w-full mt-2">
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">20</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">21</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">22</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">23</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">24</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">25</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">26</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="flex w-full mt-2">
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">27</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">28</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground bg-accent text-accent-foreground"
                                                                role="gridcell" aria-selected="true" tabindex="0"
                                                                type="button">29</button></td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">30</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base"
                                                                role="gridcell" tabindex="-1" type="button">31</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                                role="gridcell" tabindex="-1" type="button">1</button>
                                                        </td>
                                                        <td class="h-9 w-9 text-center text-sm p-0 relative [&amp;:has([aria-selected].day-range-end)]:rounded-r-md [&amp;:has([aria-selected].day-outside)]:bg-accent/50 [&amp;:has([aria-selected])]:bg-accent first:[&amp;:has([aria-selected])]:rounded-l-md last:[&amp;:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20"
                                                            role="presentation"><button name="day"
                                                                class="rdp-button_reset rdp-button h-12 w-12 text-base day-outside text-muted-foreground opacity-50 aria-selected:bg-accent/50 aria-selected:text-muted-foreground aria-selected:opacity-30"
                                                                role="gridcell" tabindex="-1" type="button">2</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6" style="opacity: 1; transform: none;">
                        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                            <div class="flex flex-col space-y-1.5 p-6">
                                <h3 class="text-2xl font-semibold leading-none tracking-tight">Availability</h3>
                                <p class="text-sm text-muted-foreground">Tuesday, July 29</p>
                            </div>
                            <div class="p-6 pt-0 space-y-3">
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                    <p class="text-sm text-muted-foreground text-center py-4">No available slots for this
                                        day.</p>
                                </div><button
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 w-full text-primary border-primary hover:bg-primary/10"
                                    type="button" aria-haspopup="dialog" aria-expanded="false"
                                    aria-controls="radix-:rc:" data-state="closed"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 12h8"></path>
                                        <path d="M12 8v8"></path>
                                    </svg> Add Time Slots</button><button
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2 w-full"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="m15 9-6 6"></path>
                                        <path d="m9 9 6 6"></path>
                                    </svg> Mark Full Day as Unavailable</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
