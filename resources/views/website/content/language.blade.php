@extends('website.master.master')
@section('title', 'Teachers - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="container mx-auto px-4 md:px-6 py-12 bg-background">
            <div class="mb-10" style="opacity: 1; transform: none;">
                <a href="{{ route('index') }}"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary hover:text-primary-foreground"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-2 h-4 w-4">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg> Back to Home</a>
                <h1 class="text-3xl md:text-4xl font-bold text-foreground text-center"><span
                        class="text-gradient-yellow-red">{{ $language->name }} Tutors</span></h1>
                <p class="text-md text-muted-foreground text-center mt-2">Explore available tutors for
                    {{ $language->name }}.
                </p>
            </div>
            <div class="space-y-8">
                @foreach ($teachers as $teacher)
                    <div class="cursor-pointer" style="opacity: 1; transform: none;">
                        <div
                            class="border border-primary/20 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                            <div class="md:flex">
                                <div
                                    class="md:w-1/3 bg-gradient-to-br from-primary/5 to-secondary/5 p-5 flex flex-col items-center justify-center text-center">
                                    <img class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover mb-3 border-4 border-primary shadow-md"
                                        alt="{{ $teacher->user->name }}"
                                        src="{{ asset('storage/' . $teacher->profile_image) }}">
                                    <h3 class="text-xl font-bold text-foreground mb-1">{{ $teacher->user->name }}</h3>
                                    @php
                                        $rating = $teacher->reviews->avg('rating') ?? 0;
                                        $reviewCount = $teacher->reviews->count();
                                        $fullStars = floor($rating);
                                        $halfStar = $rating - $fullStars >= 0.5 ? true : false;
                                    @endphp

                                    <div class="flex items-center text-yellow-500 mb-1">
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="h-4 w-4">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>
                                        @endfor

                                        @if ($halfStar)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="h-4 w-4" style="clip-path: inset(0px 50% 0px 0px);">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>
                                        @endif

                                        @for ($i = 0; $i < 5 - ceil($rating); $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>
                                        @endfor
                                        <span class="ml-1.5 text-muted-foreground text-xs">({{ $reviewCount }}
                                            reviews)</span>
                                    </div>

                                    <p class="text-secondary font-semibold text-md mb-0.5">
                                        ${{ $teacher->duration60Rate ?? 'N/A' }}/hour
                                    </p>

                                    <p class="text-primary font-semibold text-sm mb-3">
                                    </p>
                                    <a href="{{ route('tutor', ['id' => $teacher->user->id]) }}">
                                        <button
                                            class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 w-full btn-red text-sm py-2">
                                            View
                                            Profile &amp; Book
                                        </button>
                                    </a>
                                </div>
                                <div class="md:w-2/3 p-5 md:p-6">
                                    <h4 class="text-lg font-semibold text-foreground mb-2">
                                        Teaches {{ $teacher->language->name ?? $language->name }}
                                    </h4>
                                    <p class="text-muted-foreground leading-relaxed mb-4 text-sm line-clamp-3">
                                        {{ $teacher->user->teacherProfile?->about ?? 'This teacher has not added an about section yet.' }}
                                        <br>
                                        {{ $teacher->user->teacherProfile?->experience ?? 0 }}+ years of experience
                                    </p>

                                    <h5 class="text-md font-semibold text-primary mb-2 flex items-center"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1.5">
                                            <rect width="20" height="14" x="2" y="7" rx="2" ry="2">
                                            </rect>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                        </svg>Example Packages</h5>
                                    <div class="space-y-2">
                                        @forelse($teacher->packages->take(2) as $package)
                                            <div class="border border-primary/10 p-2.5 rounded-md bg-primary/5">
                                                <div class="flex justify-between items-center mb-0.5">
                                                    <p class="font-medium text-foreground text-xs">{{ $package->name }}
                                                    </p>
                                                    <p class="font-semibold text-secondary text-sm">${{ $package->price }}
                                                    </p>
                                                </div>
                                                <p class="text-xs text-muted-foreground">{{ $package->number_of_lessons }}
                                                    lessons
                                                </p>
                                            </div>
                                        @empty
                                            <p class="text-xs text-muted-foreground text-center">No packages available</p>
                                        @endforelse

                                        {{-- Show "More packages..." if teacher has more than 2 --}}
                                        @if ($teacher->packages->count() > 2)
                                            <p class="text-xs text-muted-foreground text-center mt-1">
                                                More packages on profile...
                                            </p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
