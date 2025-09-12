@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="container mx-auto px-4 md:px-6 py-12 bg-background">
            <div class="mb-10" style="opacity: 1; transform: none;">
                <a href="{{ route('find.tutor') }}">
                    <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary hover:text-primary-foreground"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 h-4 w-4">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        Back
                    </button>
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-foreground text-center"><span
                        class="text-gradient-yellow-red">Explore Our Group Lessons</span></h1>
                <p class="text-md text-muted-foreground text-center mt-2 max-w-2xl mx-auto">Learn collaboratively with
                    expert tutors and peers from around the world.</p>
            </div>
            <div class="mb-10 p-6 bg-white border border-primary/20 rounded-xl shadow-md"
                style="opacity: 1; transform: none;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="text-sm font-medium text-foreground">Language</label>
                        <div class="mt-1">
                            <select name="learn_language"
                                class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                                <option value="">Select Language</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div><label
                            class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-medium text-foreground"
                            for="price-range-filter">Price Range: $0 - $250</label><span dir="ltr"
                            data-orientation="horizontal" aria-disabled="false"
                            class="relative flex w-full touch-none select-none items-center mt-2" id="price-range-filter"
                            style="--radix-slider-thumb-transform: translateX(-50%);"><span data-orientation="horizontal"
                                class="relative h-2 w-full grow overflow-hidden rounded-full bg-secondary/20"><span
                                    data-orientation="horizontal" class="absolute h-full bg-primary"
                                    style="left: 0%; right: 0%;"></span></span><span
                                style="transform: var(--radix-slider-thumb-transform); position: absolute; left: calc(0% + 10px);"><span
                                    role="slider" aria-valuemin="0" aria-valuemax="250" aria-orientation="horizontal"
                                    data-orientation="horizontal" tabindex="0"
                                    class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                                    data-radix-collection-item="" aria-label="Minimum" aria-valuenow="0"
                                    style=""></span></span><span
                                style="transform: var(--radix-slider-thumb-transform); position: absolute; left: calc(100% - 10px);"><span
                                    role="slider" aria-valuemin="0" aria-valuemax="250" aria-orientation="horizontal"
                                    data-orientation="horizontal" tabindex="0"
                                    class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                                    data-radix-collection-item="" aria-label="Maximum" aria-valuenow="250"
                                    style=""></span></span></span></div>
                    <div>
                        <label class="text-sm font-medium text-foreground">Max Students</label>
                        <div class="mt-1">
                            <select name="learn_language"
                                class="inline-flex items-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                                <option value="">Ask Any</option>
                                <option value="">Up to 2</option>
                                <option value="">Up to 3</option>
                                <option value="">Up to 4</option>
                                <option value="">Up to 5</option>
                                <option value="">Up to 6</option>
                                <option value="">Up to 7</option>
                                <option value="">Up to 8</option>
                                <option value="">Up to 9</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mt-5">
                    @foreach ($courses as $course)
                        @php
                            // Count enrolled students for this course
                            $enrolledStudents = $course->enrollments ? $course->enrollments->count() : 0;
                        @endphp
                        <div style="opacity: 1; transform: none;" class="course-card" data-course-id="{{ $course->id }}"
                            data-title="{{ $course->title }}" data-price="{{ $course->price_per_student }}"
                            data-teacher="{{ $course->teacher->name }}" data-weeks="{{ $course->lessons_per_week }}"
                            data-days="{{ implode(',', collect($course['days'])->pluck('day')->toArray()) }}"
                            data-max-students="{{ $course->max_students }}" data-spots="{{ $enrolledStudents }}">
                            <div
                                class="rounded-lg border bg-card text-card-foreground overflow-hidden shadow-lg hover:shadow-xl transition-shadow border-primary/20 flex flex-col h-full">
                                <div class="flex flex-col space-y-1.5 p-0">
                                    <div class="relative"><img class="w-full h-48 object-cover"
                                            alt="{{ $course->teacher->name }} teaching French"
                                            src="https://images.unsplash.com/photo-1543269865-cbf427effbad">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                        <div class="absolute bottom-4 left-4">
                                            <div class="flex items-center mb-1"><img
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-white mr-2"
                                                    alt="{{ $course->teacher->name }}"
                                                    src="https://images.unsplash.com/photo-1692274634343-aa1bc1828b7c"><span
                                                    class="text-white text-sm font-medium">{{ $course->teacher->name }}</span>
                                            </div>
                                            <h3 class="font-semibold tracking-tight text-white text-xl" id="course-title">
                                                {{ $course->title }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3 flex-grow">
                                    <p class="text-muted-foreground text-sm line-clamp-3">{{ $course->description }}
                                    </p>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs text-foreground">
                                        <div class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-3.5 w-3.5 mr-1.5 text-primary">
                                                <line x1="12" x2="12" y1="2" y2="22">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg> Price: <span class="font-semibold ml-1"
                                                id="course-price">${{ $course->price_per_student }}
                                                USD</span>
                                        </div>

                                        <div class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-3.5 w-3.5 mr-1.5 text-primary">
                                                <rect width="18" height="18" x="3" y="4" rx="2"
                                                    ry="2">
                                                </rect>
                                                <line x1="16" x2="16" y1="2" y2="6">
                                                </line>
                                                <line x1="8" x2="8" y1="2" y2="6">
                                                </line>
                                                <line x1="3" x2="21" y1="10" y2="10">
                                                </line>
                                                <path d="M8 14h.01"></path>
                                                <path d="M12 14h.01"></path>
                                                <path d="M16 14h.01"></path>
                                                <path d="M8 18h.01"></path>
                                                <path d="M12 18h.01"></path>
                                                <path d="M16 18h.01"></path>
                                            </svg> Duration: {{ $course->lessons_per_week }} Weeks</div>

                                        @php
                                            $days = collect($course['days'])
                                                ->map(function ($item) {
                                                    return \Carbon\Carbon::parse($item['day'])->format('l, d M Y') .
                                                        ' at ' .
                                                        date('h:i A', strtotime($item['time']));
                                                })
                                                ->toArray();
                                            $dayCount = count($days);
                                            $dayList = '';
                                            if ($dayCount === 1) {
                                                $dayList = $days[0];
                                            } elseif ($dayCount === 2) {
                                                $dayList = implode(' & ', $days);
                                            } else {
                                                $dayList =
                                                    implode(', ', array_slice($days, 0, $dayCount - 1)) .
                                                    ' & ' .
                                                    $days[$dayCount - 1];
                                            }
                                        @endphp
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="h-3.5 w-3.5 mr-1.5 text-primary">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            Schedule:{{ $dayCount }} times a week, {{ $dayList }}
                                        </div>
                                        <div class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-3.5 w-3.5 mr-1.5 text-primary">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg> Spots: {{ $enrolledStudents }}/{{ $course->max_students }}</div>
                                    </div>

                                    <div class="pt-2">
                                        <h4 class="text-xs font-semibold text-primary mb-1">Key Features:</h4>

                                        @php
                                            $features = collect((array) $course->features)
                                                ->map(fn($f) => trim($f, '[]"'))
                                                ->filter(fn($f) => $f !== '');
                                        @endphp

                                        @if ($features->isEmpty())
                                            <p class="text-xs text-muted-foreground italic">No features added</p>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach ($features as $feature)
                                                    <li class="flex items-center text-xs text-muted-foreground">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="h-3 w-3 mr-1.5 text-green-500 flex-shrink-0">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                        </svg>
                                                        {{ ucfirst($feature) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center p-4 mt-auto"><button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full btn-red"
                                        data-course-id="{{ $course->id }}"
                                        data-price="{{ $course->price_per_student }}"
                                        onclick="goToGroupCheckout(this)">Join
                                        Course
                                    </button></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
    </main>
    <script>
        document.querySelectorAll('.course-card').forEach((card, index) => {
            const courseInfo = {
                id: card.dataset.courseId,
                title: card.dataset.title,
                price: card.dataset.price,
                teacher: card.dataset.teacher,
                weeks: card.dataset.weeks,
                days: card.dataset.days,
                maxStudents: card.dataset.maxStudents,
                spotsFilled: card.dataset.spots
            };

            console.log(`Course ${index + 1}:`, courseInfo);
        });

        function goToGroupCheckout(button) {
            const courseId = button.dataset.courseId;
            const price = button.dataset.price;

            const url = `/student/checkout?type=group&value=${courseId}&price=${price}`;
            window.location.href = url;
        }
    </script>
@endsection
