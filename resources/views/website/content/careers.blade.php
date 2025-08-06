@extends('website.master.master')
@section('title', 'Careers - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="container mx-auto px-4 md:px-6 py-12 bg-background">
            <div class="mb-10" style="opacity: 1; transform: none;">
                <a href="{{ route('index') }}">
                    <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary hover:text-primary-foreground"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 h-4 w-4">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg> Back
                    </button>
                </a>
                @if (session('success'))
                    <div
                        class="alert alert-success bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-16 w-16 mx-auto text-primary mb-4">
                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <h1 class="text-4xl md:text-5xl font-bold text-foreground">Join Our <span
                            class="text-gradient-yellow-red">Team</span></h1>
                    <p class="mt-4 text-lg text-muted-foreground max-w-xl mx-auto">We're building the future of language
                        learning and looking for passionate individuals to help us on our mission.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach ($careers as $career)
                    <div style="opacity: 1; transform: none;">
                        <div
                            class="rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl transition-shadow border-primary/20">
                            <div class="flex flex-col space-y-1.5 p-6">
                                <h3 class="font-semibold tracking-tight text-2xl text-primary">
                                    {{ $career->title }}
                                </h3>
                                <p class="text-sm text-muted-foreground">{{ $career->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="p-6 pt-0 space-y-3">
                                <div class="flex items-center text-sm text-foreground"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-4 w-4 mr-2 text-secondary">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg> {{ $career->location === 'on_site' ? 'On-Site' : ucfirst($career->location) }}
                                </div>
                                <div class="flex items-center text-sm text-foreground"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-4 w-4 mr-2 text-secondary">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg> {{ ucwords(str_replace('_', ' ', $career->type)) }}
                                </div>
                                <div class="flex items-center text-sm text-foreground"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-4 w-4 mr-2 text-secondary">
                                        <line x1="12" x2="12" y1="2" y2="22"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg> {{ $career->salary }} Annually</div>
                                <p class="text-muted-foreground text-sm pt-2 line-clamp-3">Join our passionate engineering
                                    team
                                    to build and enhance the fluentAll platform. You will be responsible for developing new
                                    features, improving user experience, and ensuring code quality.</p>
                                <div class="pt-2">
                                    <h4 class="text-xs font-semibold text-primary mb-1">Key Qualifications:</h4>
                                    <ul class="list-disc list-inside space-y-0.5">
                                        {{ $career->description }}
                                    </ul>
                                </div>
                            </div>
                            <a href="{{ route('apply.form', $career->id) }}">
                                <div class="flex items-center p-6 pt-0">
                                    <button
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full btn-red">
                                        Apply Now <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" x2="21" y1="14" y2="3"></line>
                                        </svg>
                                    </button>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-16 text-center bg-primary/10 p-8 rounded-xl border border-primary/20" style="opacity: 1;">
                <h2 class="text-2xl font-semibold text-foreground mb-3">Can't find a suitable role?</h2>
                <p class="text-muted-foreground mb-6 max-w-md mx-auto">We're always looking for talented individuals. Send
                    us your resume and tell us how you can contribute to fluentAll!</p>
                <button
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 btn-yellow-outline">
                    <a href="{{ route('apply.general') }}">Submit General Application</a> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="ml-2 h-4 w-4">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" x2="21" y1="14" y2="3"></line>
                    </svg>
                </button>
            </div>
        </div>
    </main>
@endsection
