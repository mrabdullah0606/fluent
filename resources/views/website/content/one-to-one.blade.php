@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <style>
        .slider-container {
            width: 300px;
            position: relative;
            /*margin: 18px;*/
        }

        .slider-track {
            position: absolute;
            height: 5px;
            background: #ccc;
            width: 100%;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            border-radius: 5px;
        }

        .range {
            position: absolute;
            height: 5px;
            background: orange;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            border-radius: 5px;
        }

        input[type=range] {
            -webkit-appearance: none;
            position: absolute;
            width: 100%;
            pointer-events: none;
            background: none;
            z-index: 3;
            top: 50%;
            transform: translateY(-50%);
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: blue;
            cursor: pointer;
            pointer-events: auto;
            position: relative;
            z-index: 4;
        }

        .range-values {
            margin-bottom: 30px;
            margin-top: -17px;
        }
    </style>
    <main class="flex-grow">
        <div class="container mx-auto px-4 md:px-6 py-8 md:py-12 bg-background">
            <h1 class="text-3xl md:text-4xl font-bold text-foreground text-center mb-8 md:mb-10"
                style="opacity: 1; transform: none;"><span class="text-gradient-yellow-red">Find Your One-on-One Tutor</span>
            </h1>
            <form method="GET" action="{{ route('one.on.one.tutors') }}">
                <div class="mb-8 p-4 md:p-6 border border-primary/20 bg-white rounded-xl shadow-lg" style="opacity: 1;">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 items-end">
                        <!-- Language I Want to Learn -->
                        <div>
                            <label class="text-sm font-medium text-foreground">Language I Want to Learn</label>
                            <div class="mt-1">
                                <select name="learn_language"
                                    class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                                    <option value="">Select Language</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->name }}"
                                            {{ request('learn_language') == $language->name ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Price Range (Dual Range Slider) -->
                        <div class="slider-container">
                            <div class="range-values">
                                Price Range: $<span id="min-val">0</span> - $<span id="max-val">75</span>
                            </div>
                            <div class="slider-track"></div>
                            <div class="range" id="range"></div>
                            <input type="range" id="minRange" min="0" max="75" value="0"
                                step="1">
                            <input type="range" id="maxRange" min="0" max="75" value="75"
                                step="1">
                        </div>
                        <!-- Tutor Speaks -->
                        <div>
                            <label class="text-sm font-medium text-foreground">Tutor Speaks</label>
                            <div class="mt-1">
                                <select name="speaks"
                                    class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                                    <option value="">Select Language</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->name }}"
                                            {{ request('learn_language') == $language->name ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Tutor From -->
                        <div>
                            <label class="text-sm font-medium text-foreground">Tutor From</label>
                            <div class="mt-1">
                                <select name="country"
                                    class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country }}"
                                            {{ request('country') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Search and Buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-grow">
                            <input type="text" name="name" value="{{ request('name') }}"
                                class="pl-10 w-full h-10 rounded-md border bg-background px-3 py-2 text-sm border-input"
                                placeholder="Search by tutor name..." />
                        </div>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border bg-background h-10 px-4 py-2 border-primary text-primary hover:bg-primary hover:text-white">
                            Apply Filters
                        </button>
                        <a href=""
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border bg-background h-10 px-4 py-2 border-secondary text-secondary hover:bg-secondary hover:text-white">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>



            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                @foreach ($teachers as $teacher)
                    <div class="border border-primary/20 bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-shadow md:flex gap-6"
                        style="opacity: 1; transform: none;">
                        <div class="md:w-1/3 flex flex-col items-center text-center mb-4 md:mb-0">
                            @if ($teacher->teacherProfile && $teacher->teacherProfile->profile_image)
                                <img class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover mb-3 border-4 border-primary shadow-md"
                                    alt="{{ $teacher->name }}"
                                    src="{{ asset('storage/' . $teacher->teacherProfile->profile_image) }}">
                            @else
                                <div class="text-gray-500 text-sm">No image updated</div>
                            @endif

                            <h3 class="text-xl font-bold text-foreground mb-1">{{ $teacher->name }}</h3>
                           <div class="flex items-center text-yellow-500 mb-1">
                            @php
                                $fullStars = floor($teacher->average_rating); // full stars
                                $halfStar = ($teacher->average_rating - $fullStars) >= 0.5; // half star if needed
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // remaining empty stars
                            @endphp

                            {{-- Full Stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 
                                                     18.18 21.02 12 17.77 5.82 21.02 
                                                     7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                            @endfor

                            {{-- Half Star --}}
                            @if ($halfStar)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 24 24">
                                    <defs>
                                        <linearGradient id="half-grad">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="transparent"/>
                                        </linearGradient>
                                    </defs>
                                    <polygon fill="url(#half-grad)" stroke="currentColor" stroke-width="2"
                                             points="12 2 15.09 8.26 22 9.27 17 14.14 
                                                     18.18 21.02 12 17.77 5.82 21.02 
                                                     7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                            @endif

                            {{-- Empty Stars --}}
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" viewBox="0 0 24 24">
                                    <polygon fill="none" stroke="currentColor" stroke-width="2"
                                             points="12 2 15.09 8.26 22 9.27 17 14.14 
                                                     18.18 21.02 12 17.77 5.82 21.02 
                                                     7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                            @endfor
                            <span class="ml-1.5 text-muted-foreground text-xs">
                                ({{ $teacher->reviews_count }} {{ Str::plural('review', $teacher->reviews_count) }})
                            </span>
                        </div>

                                                    <p class="text-secondary font-semibold text-md mb-0.5"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="inline h-4 w-4 mr-0.5">
                                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                        </svg>
                                                        {{ $teacher->duration_60 ?? 'N/A' }}/hour
                                                    </p>
                                                    <p class="text-primary font-semibold text-sm mb-2">Trial: $5</p>
                                                    <button
                                                        class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 btn-red w-full text-sm py-2">
                                                        <a href="{{ route('tutor', ['id' => $teacher->id]) }}" class="...">View Profile &amp;
                                                            Book</a>

                                                    </button>
                        </div>
                        <div class="md:w-2/3">
                            <p class="text-muted-foreground text-sm leading-relaxed mb-3 line-clamp-3">
                                {{ $teacher->teacherProfile->headline ?? 'N/A' }}</p>
                            <div class="text-xs space-y-1 mb-3">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <path d="m5 8 6 6"></path>
                                        <path d="m4 14 6-6 2-3"></path>
                                        <path d="M2 5h12"></path>
                                        <path d="M7 2h1"></path>
                                        <path d="m22 22-5-10-5 10"></path>
                                        <path d="M14 18h6"></path>


                                    </svg>

                                    {{-- Teaches: {{ implode(', ', $teacherLanguages->teaches) ?: 'N/A' }} --}}
                                    Teaches:
                                    {{ !empty($teacher->teaches_names) ? implode(', ', $teacher->teaches_names) : 'N/A' }}


                                </p>
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" x2="22" y1="12" y2="12"></line>
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                        </path>
                                    </svg> Speaks: {{ $teacher->teacherProfile->speaks ?? 'N/A' }}</p>
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg> From: {{ $teacher->teacherProfile->country ?? 'N/A' }}</p>
                            </div>
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
                @endforeach
            </div>
        </div>
    </main>
    <script>
        const minRange = document.getElementById('minRange');
        const maxRange = document.getElementById('maxRange');
        const range = document.getElementById('range');
        const minValText = document.getElementById('min-val');
        const maxValText = document.getElementById('max-val');

        function updateRange() {
            const min = parseInt(minRange.value);
            const max = parseInt(maxRange.value);

            if (min > max - 1) {
                minRange.value = max - 1;
            }
            if (max < min + 1) {
                maxRange.value = min + 1;
            }

            const percent1 = (minRange.value / 75) * 100;
            const percent2 = (maxRange.value / 75) * 100;

            range.style.left = percent1 + "%";
            range.style.width = (percent2 - percent1) + "%";

            minValText.textContent = minRange.value;
            maxValText.textContent = maxRange.value;
        }

        minRange.addEventListener('input', updateRange);
        maxRange.addEventListener('input', updateRange);

        updateRange(); // Initialize
    </script>

@endsection
