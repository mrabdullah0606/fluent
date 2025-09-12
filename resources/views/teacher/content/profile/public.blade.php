@extends('teacher.master.master')
@section('title', 'Public Profile - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 min-h-screen py-8 md:py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-md mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 mr-3">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <p>This is a preview of how your profile appears to students.
                        <a href="{{ route('teacher.profile.edit') }}"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 underline-offset-4 hover:underline p-0 h-auto ml-1 text-yellow-900">Edit
                            Profile</a>
                    </p>
                </div>
                <div style="opacity: 1; transform: none;">
                    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 mb-8 md:flex items-center">
                        <div
                            class="md:w-1/3 flex flex-col items-center md:items-start text-center md:text-left mb-6 md:mb-0">
                            <span
                                class="relative flex shrink-0 rounded-full w-32 h-32 md:w-40 md:h-40 mb-4 border-4 border-yellow-400 cursor-pointer">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-full text-4xl bg-yellow-400 text-white overflow-hidden">
                                    @if ($teacherProfile && $teacherProfile->profile_image)
                                        <img src="{{ asset('storage/' . $teacherProfile->profile_image) }}"
                                            class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span
                                            class="text-white font-bold">{{ strtoupper(substr($user->name ?? 'T', 0, 1)) }}</span>
                                    @endif
                                </span>
                            </span>

                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                                {{ $user->name ?? 'Unnamed Teacher' }}
                            </h1>
                            <p class="text-primary text-md mt-1">
                                {{ $teacherProfile?->headline ?? 'No headline added yet' }}
                            </p>
                        </div>

                        <!-- Right Section -->
                        <div class="md:w-2/3 md:pl-8 space-y-4">
                            <!-- Two-column layout: text info (left) + video (right) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <!-- Left: Text Info -->
                                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 text-sm">
                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        From: {{ $teacherProfile?->country ?? 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-5 w-5 mr-2 text-primary">
                                            <path d="m5 8 6 6"></path>
                                            <path d="m4 14 6-6 2-3"></path>
                                            <path d="M2 5h12"></path>
                                            <path d="M7 2h1"></path>
                                            <path d="m22 22-5-10-5 10"></path>
                                            <path d="M14 18h6"></path>
                                        </svg>
                                        Teaches: {{ !empty($teacherProfile?->teaches) ? implode(', ', $languages) : 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="h-10 w-10 mr-2 text-primary">
                                            <circle cx="12" cy="8" r="6"></circle>
                                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                        </svg>
                                        Experience: {{ $teacherProfile?->experience ?? 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="2" x2="22" y1="12" y2="12"></line>
                                            <path
                                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                            </path>
                                        </svg>
                                        Speaks: {{ !empty($languages) ? implode(', ', $languages) : 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700 col-span-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Rate: ${{ number_format($teacherProfile?->rate_per_hour ?? 0, 2) }}/hour
                                    </div>
                                </div>

                                <!-- Right: Video -->
                                <div>
                                    @if ($introVideo)
                                        <!-- Uploaded Video -->
                                        <video controls class="w-full h-48 md:h-64 rounded-lg shadow-md">
                                            <source src="{{ asset('storage/' . $introVideo) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <p>No video uploaded</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons under both -->
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                                <button
                                    class="inline-flex items-center justify-center text-sm h-10 w-full sm:w-auto bg-red-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md"
                                    disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
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
                                    </svg> Book Lesson
                                </button>
                                <button
                                    class="inline-flex items-center justify-center text-sm h-10 w-full sm:w-auto border border-primary text-primary font-semibold py-3 px-6 rounded-lg"
                                    disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg> Message Tutor
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-8">
                            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg> About Me</h2>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                    {{ $teacherProfile?->about_me ?? 'No information provided yet.' }}
                                </p>

                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                                        <circle cx="12" cy="8" r="6"></circle>
                                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                    </svg>
                                    Specialties
                                </h2>

                                <div class="flex flex-wrap gap-2">
                                    @if (!empty($teacherProfile?->teaching_style))
                                        @foreach (explode(',', $teacherProfile->teaching_style) as $style)
                                            @if (trim($style) != '')
                                                <div
                                                    class="inline-flex items-center rounded-full border px-2.5 py-0.5 
                        text-xs font-semibold transition-colors focus:outline-none 
                        focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent 
                        bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                    {{ trim(ucwords($style)) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">No specialties added yet.</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Lesson Packages
                            </h2>

                            <div class="bg-red-50 p-6 rounded-xl shadow-md border border-red-200">
                                <div class="space-y-4">
                                    @if ($teacher->lessonPackages && $teacher->lessonPackages->where('is_active', true)->count() > 0)
                                        @foreach ($teacher->lessonPackages->where('is_active', true) as $package)
                                            @php
                                                $discountMap = [1 => 5, 2 => 10, 3 => 15];
                                                $discountPercentage = $discountMap[$package->package_number] ?? 0;
                                                $originalPrice = (float) $package->price;
                                            @endphp

                                            <div
                                                class="border border-red-300 p-4 rounded-lg bg-white hover:shadow-lg transition-shadow">
                                                <h3 class="font-semibold text-red-600 text-lg">{{ $package->name }}</h3>
                                                @if ($discountPercentage > 0)
                                                    <p class="text-sm font-bold text-green-600">Save
                                                        {{ $discountPercentage }}%</p>
                                                @endif

                                                <div class="text-xs text-gray-600 mb-2">
                                                    <p><strong>{{ $package->number_of_lessons }}</strong> lessons</p>
                                                    <p>
                                                        <span class="font-semibold text-black text-base">
                                                            ${{ number_format($originalPrice, 2) }}
                                                        </span>
                                                    </p>

                                                    @if ($package->duration_per_lesson)
                                                        <p><strong>{{ $package->duration_per_lesson }}</strong> minutes per
                                                            lesson</p>
                                                    @endif
                                                </div>

                                                <ul class="text-xs text-gray-500 space-y-1 my-2">
                                                    <li class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                        </svg>
                                                        Flexible scheduling
                                                    </li>
                                                    <li class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="h-3 w-3 mr-2 text-green-500">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                        </svg>
                                                        Personalized learning
                                                    </li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="border border-red-300 p-4 rounded-lg bg-white text-center">
                                            <p class="text-gray-500 text-sm">No lesson packages available at the moment.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="bg-white p-6 md:p-8 rounded-xl shadow-md border border-gray-200 mt-12">
                        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-7 w-7 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            Student Reviews ({{ $reviewsCount }})
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($reviews as $review)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3">
                                                <span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted text-gray-700">
                                                    {{ strtoupper(substr($review->student->name ?? 'S', 0, 1)) }}
                                                </span>
                                            </span>
                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    {{ $review->student->name ?? 'Anonymous' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"
                                                    viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon
                                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 italic mt-3">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <p class="text-gray-500">No reviews yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
