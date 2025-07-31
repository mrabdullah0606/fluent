@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="container mx-auto px-4 md:px-6 py-8 md:py-12 bg-background">
            <h1 class="text-3xl md:text-4xl font-bold text-foreground text-center mb-8 md:mb-10"
                style="opacity: 1; transform: none;"><span class="text-gradient-yellow-red">Find Your One-on-One Tutor</span>
            </h1>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                @foreach ($teachers as $teacher)
                    <div class="border border-primary/20 bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-shadow md:flex gap-6"
                        style="opacity: 1; transform: none;">
                        <div class="md:w-1/3 flex flex-col items-center text-center mb-4 md:mb-0"><img
                                class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover mb-3 border-4 border-primary shadow-md"
                                alt="{{ $teacher->name }}"
                                src="https://images.unsplash.com/photo-1540212389296-89cb5df13fa8">
                            <h3 class="text-xl font-bold text-foreground mb-1">{{ $teacher->name }}</h3>
                            <div class="flex items-center text-yellow-500 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 fill-current">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 fill-current">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 fill-current">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4 fill-current">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4" style="clip-path: inset(0px 50% 0px 0px);">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <span class="ml-1.5 text-muted-foreground text-xs">(120 reviews)</span>
                            </div>
                            <p class="text-secondary font-semibold text-md mb-0.5"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="inline h-4 w-4 mr-0.5">
                                    <line x1="12" x2="12" y1="2" y2="22"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>25/hour</p>
                            <p class="text-primary font-semibold text-sm mb-2">Trial: $5</p>
                            <button
                                class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 btn-red w-full text-sm py-2">
                                <a href="{{ route('tutor', ['id' => $teacher->id]) }}" class="...">View Profile &amp;
                                    Book</a>

                            </button>
                        </div>
                        <div class="md:w-2/3">
                            <p class="text-muted-foreground text-sm leading-relaxed mb-3 line-clamp-3">Experienced Russian
                                and
                                English tutor. TEFL certified. Focus on conversational skills.</p>
                            <div class="text-xs space-y-1 mb-3">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <path d="m5 8 6 6"></path>
                                        <path d="m4 14 6-6 2-3"></path>
                                        <path d="M2 5h12"></path>
                                        <path d="M7 2h1"></path>
                                        <path d="m22 22-5-10-5 10"></path>
                                        <path d="M14 18h6"></path>
                                    </svg> Teaches: English, Russian</p>
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" x2="22" y1="12" y2="12"></line>
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                        </path>
                                    </svg> Speaks: English, Russian, German</p>
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="inline h-3.5 w-3.5 mr-1 text-primary">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg> From: Russia</p>
                            </div><button
                                class="inline-flex items-center justify-center font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-9 rounded-md px-3 w-full border-primary text-primary hover:bg-primary hover:text-primary-foreground text-xs"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-3.5 w-3.5 mr-1.5">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg> Message Elena</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
