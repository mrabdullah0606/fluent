@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-white min-h-screen py-8 md:py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div style="opacity: 1; transform: none;">
                    <div
                        class="bg-gray-50 p-6 md:p-8 rounded-xl shadow-lg border border-yellow-300 mb-8 md:flex items-center">
                        <div
                            class="md:w-1/3 flex flex-col items-center md:items-start text-center md:text-left mb-6 md:mb-0">
                            <span
                                class="relative flex shrink-0 overflow-hidden rounded-full w-32 h-32 md:w-40 md:h-40 mb-4 border-4 border-yellow-400"><span
                                    class="flex h-full w-full items-center justify-center rounded-full text-4xl bg-yellow-400 text-white">EP</span></span>
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $teacher->name }}</h1>
                            <p class="text-yellow-600 text-md mt-1">Experienced TEFL Certified Tutor | Russian &amp; English
                            </p>
                            <div class="flex items-center mt-2 text-gray-700"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="h-5 w-5 text-yellow-500 fill-yellow-500 mr-1">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg><span class="font-semibold">4.5</span><span class="ml-1">(120 reviews)</span></div>
                            <div
                                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent mt-3 bg-green-500 hover:bg-green-600 text-white">
                                Active Now</div>
                        </div>
                        <div class="md:w-2/3 md:pl-8 space-y-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center text-gray-700"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5 mr-2 text-yellow-500">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg> From: 🇷🇺 Russia</div>
                                <div class="flex items-center text-gray-700"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5 mr-2 text-yellow-500">
                                        <path d="m5 8 6 6"></path>
                                        <path d="m4 14 6-6 2-3"></path>
                                        <path d="M2 5h12"></path>
                                        <path d="M7 2h1"></path>
                                        <path d="m22 22-5-10-5 10"></path>
                                        <path d="M14 18h6"></path>
                                    </svg> Teaches: English, Russian</div>
                                <div class="flex items-center text-gray-700 col-span-2 sm:col-span-1"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5 mr-2 text-yellow-500">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" x2="22" y1="12" y2="12"></line>
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                        </path>
                                    </svg> Speaks: English (Native), Russian (Native), German (B1)</div>
                                <div class="flex items-center text-gray-700"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5 mr-2 text-yellow-500">
                                        <circle cx="12" cy="8" r="6"></circle>
                                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                    </svg> Experience: 4+ years</div>
                                <div class="flex items-center text-gray-700"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5 mr-2 text-yellow-500">
                                        <line x1="12" x2="12" y1="2" y2="22"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg> Rate: $25/hour</div>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mt-6"><button
                                    class="inline-flex items-center justify-center text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2">
                                        </rect>
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
                                    <a href="{{ route('tutor.booking', ['id' => $teacher->id]) }}">
                                        Book Lesson
                                    </a>
                                </button>
                                @auth
                                    <a href="{{ route('student.chat.index', $teacher->id) }}" class="btn">
                                        <button
                                            class="inline-flex items-center justify-center font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-9 rounded-md px-3 w-full border-primary text-primary hover:bg-primary hover:text-primary-foreground text-xs"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 mr-1.5">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg> Message {{ $teacher->name }}</button>
                                    </a>
                                @endauth
                                @guest
                                    <div>
                                        Please <a href="{{ route('student.login') }}"><button
                                                class="btn btn-dark">Login</button></a> to chat with this
                                        teacher.
                                    </div>
                                @endguest

                            </div>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-8">
                            <div class="bg-gray-50 p-6 rounded-xl shadow-md border border-yellow-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="mr-2 h-6 w-6 text-yellow-500">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg> About Me</h2>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">Privet! I'm Elena, a
                                    passionate language tutor specializing in Russian and English. With my TEFL
                                    certification and years of experience, I create engaging lessons focused on your
                                    conversational fluency and understanding of culture. Let's explore languages together!
                                </p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-xl shadow-md border border-yellow-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="mr-2 h-6 w-6 text-yellow-500">
                                        <circle cx="12" cy="8" r="6"></circle>
                                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                    </svg> Specialties</h2>
                                <div class="flex flex-wrap gap-2">
                                    <div
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-secondary/80 bg-yellow-100 text-yellow-800 border-yellow-300">
                                        Conversational Russian</div>
                                    <div
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-secondary/80 bg-yellow-100 text-yellow-800 border-yellow-300">
                                        English for Beginners</div>
                                    <div
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-secondary/80 bg-yellow-100 text-yellow-800 border-yellow-300">
                                        Cultural Immersion</div>
                                    <div
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-secondary/80 bg-yellow-100 text-yellow-800 border-yellow-300">
                                        Pronunciation</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-xl shadow-md border border-yellow-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Education &amp; Certifications</h2>
                                <p class="text-gray-600 mb-2"><strong>Education:</strong> BA in Linguistics, Moscow State
                                    University</p>
                                <p class="text-gray-600"><strong>Certifications:</strong> TEFL Certified</p>
                            </div>
                        </div>
                        <div class="md:col-span-1 space-y-8">
                            <div class="bg-red-50 p-6 rounded-xl shadow-md border border-red-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-red-700 mb-4 flex items-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-6 w-6 text-red-500">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg> Lesson Packages</h2>
                                <div class="space-y-4">
                                    <div
                                        class="border border-red-300 p-4 rounded-lg bg-white hover:shadow-lg transition-shadow">
                                        <h3 class="font-semibold text-red-600 text-lg">5-Lesson Package</h3>
                                        <p class="text-sm font-bold text-green-600">Save 5%</p>
                                        <ul class="text-xs text-gray-500 space-y-1 my-2">
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Flexible scheduling</li>
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Personalized learning</li>
                                        </ul><button
                                            class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 rounded-md px-3 w-full bg-red-500 hover:bg-red-600 text-white">Select
                                            Package</button>
                                    </div>
                                    <div
                                        class="border border-red-300 p-4 rounded-lg bg-white hover:shadow-lg transition-shadow">
                                        <h3 class="font-semibold text-red-600 text-lg">10-Lesson Package</h3>
                                        <p class="text-sm font-bold text-green-600">Save 10%</p>
                                        <ul class="text-xs text-gray-500 space-y-1 my-2">
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Flexible scheduling</li>
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Personalized learning</li>
                                        </ul><button
                                            class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 rounded-md px-3 w-full bg-red-500 hover:bg-red-600 text-white">Select
                                            Package</button>
                                    </div>
                                    <div
                                        class="border border-red-300 p-4 rounded-lg bg-white hover:shadow-lg transition-shadow">
                                        <h3 class="font-semibold text-red-600 text-lg">20-Lesson Package</h3>
                                        <p class="text-sm font-bold text-green-600">Save 20%</p>
                                        <ul class="text-xs text-gray-500 space-y-1 my-2">
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Flexible scheduling</li>
                                            <li class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>Personalized learning</li>
                                        </ul><button
                                            class="inline-flex items-center justify-center text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 rounded-md px-3 w-full bg-red-500 hover:bg-red-600 text-white">Select
                                            Package</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-6 md:p-8 rounded-xl shadow-md border border-yellow-200 mt-12"
                        style="opacity: 1; transform: none;">
                        <h2 class="text-2xl md:text-3xl font-semibold text-yellow-700 mb-6 flex items-center"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mr-3 h-7 w-7 text-yellow-500">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                </polygon>
                            </svg> Student Reviews (120)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded-lg border border-yellow-300"
                                style="opacity: 1; transform: none;">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center"><span
                                            class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                class="flex h-full w-full items-center justify-center rounded-full bg-muted">J</span></span>
                                        <div>
                                            <p class="font-semibold text-gray-800">John D.</p>
                                            <p class="text-xs text-gray-500">June 1, 2025</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg></div>
                                </div>
                                <p class="text-sm text-gray-600 italic mt-3">"Elena is fantastic!"</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-yellow-300"
                                style="opacity: 1; transform: none;">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center"><span
                                            class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                class="flex h-full w-full items-center justify-center rounded-full bg-muted">A</span></span>
                                        <div>
                                            <p class="font-semibold text-gray-800">Anna S.</p>
                                            <p class="text-xs text-gray-500">May 29, 2025</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-500 fill-yellow-500">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-4 w-4 text-yellow-200 fill-yellow-200">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg></div>
                                </div>
                                <p class="text-sm text-gray-600 italic mt-3">"Good for Russian basics."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
