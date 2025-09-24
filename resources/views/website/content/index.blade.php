@extends('website.master.master')
@section('title', 'Home - FluentAll')
@section('content')

    {{-- <div class="container d-flex justify-content-center align-items-center min-vh-100 text-center">
        <div class="container-fluid">
            <!-- Image -->
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/website/images/hero-image.jpeg') }}"
                    class="img-fluid rounded-3 shadow border border-warning border-3"
                    style="height: 260px; width: 100%; object-fit: cover; max-width: 650px;" alt="Online Languages">
            </div>
            <!-- Heading -->
            <h1 class="fw-bold mt-4">
                Start your journey and learn with <br>
                <span class="text-warning">best online</span>
                <span style="color: red;">tutors!</span>
            </h1>
            <!-- Subheading -->
            <p class="text-muted mt-2">
                Unlock your potential. Master new languages. Connect with the world on FluentAll.
            </p>
            <!-- Down arrow -->
            <div class="mt-4 text-center">
                <i class="bi bi-chevron-down text-warning fs-2 move-icon"></i>
            </div>
        </div>
    </div> --}}
    <section class="relative py-20 md:py-28 hero-pattern-custom flex items-center justify-center text-center">
        <div class="absolute inset-0 bg-gradient-to-b from-white/30 via-transparent to-white/30"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl mx-auto" style="opacity: 1; transform: none;">
                <div class="mb-8"><img
                        class="w-full h-56 md:h-80 object-cover rounded-xl shadow-2xl border-4 border-warning/50"
                        alt="Student learning online with a tutor using FluentAll platform"
                        src="https://images.unsplash.com/photo-1673515336170-3bf951ddb5a8"></div>
                <h1 class="text-4xl md:text-5xl font-bold text-foreground mb-6 leading-tight">{{ __('welcome.heading') }}
                    <span class="text-gradient-yellow-red">{{ __('welcome.headingColor') }}</span>
                </h1>
                <p class="text-lg md:text-xl text-muted-foreground mb-10">{{ __('welcome.subHeading') }}</p>
                <div style="transform: translateY(4.56376px) translateZ(0px);"><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-10 w-10 text-warning mx-auto">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg></div>
            </div>
        </div>
    </section>
    {{-- <div class="container-fluid" style="background-color:#f8f9fa;">
        <div class="container py-5">
            <h2 class="text-center fw-bold mb-2">{{ __('welcome.exploreLanguages') }}</h2>
            <p class="text-center text-muted mb-5">{{ __('welcome.languagesSubHeading') }}</p>
            <div class="row g-4 justify-content-center">
                <!-- Card 1 -->
                @foreach ($languages as $language)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <a href="{{ route('languages.teachers', $language->id) }}" class="view-tutors text-decoration-none">
                            <div class="p-4 text-center language-card h-100">
                                <div class="language-code">{{ $language->symbol }}</div>
                                <div class="language-name">{{ $language->name }}</div>
                                <p class="tutor-info">
                                    <i class="bi bi-person"></i> {{ $language->teachers_count }} tutors available
                                </p>
                                View Tutors <i class="bi bi-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <!-- Show More Button -->
            <div class="text-center mt-5">
                <button class="btn btn-danger px-4 py-2 custom-btn">Show More Languages</button>
            </div>
        </div>
    </div> --}}\

    <section id="languages" class="py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4" style="opacity: 1; transform: none;">
                {{ __('welcome.exploreLanguages') }}</h2>
            <p class="text-md text-muted-foreground mb-12 max-w-2xl mx-auto" style="opacity: 1; transform: none;">
                {{ __('welcome.languagesSubHeading') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 mb-8">
                @foreach ($languages as $language)
                    <div style="opacity: 1; transform: none;">
                        <a href="{{ route('languages.teachers', $language->id) }}">

                            <div
                                class="p-5 md:p-6 rounded-xl shadow-lg text-foreground cursor-pointer h-48 md:h-56 flex flex-col justify-between items-center text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-warning/20 bg-white hover:border-warning">
                                <div class="text-4xl md:text-5xl mb-2">{{ $language->symbol }}</div>
                                <h3 class="text-xl md:text-2xl font-semibold text-foreground">{{ $language->name }}</h3>
                                <div class="flex items-center text-xs text-muted-foreground mt-1"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-3 w-3 mr-1.5 text-warning">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg> {{ $language->teachers_count }} {{ __('welcome.tutorsAvailable') }}</div>
                                <div
                                    class="mt-3 flex items-center text-sm text-warning font-medium group-hover:text-secondary transition-colors">
                                    {{ __('welcome.viewTutors') }} <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="ml-1.5 h-4 w-4">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg></div>
                            </div>
                        </a>

                    </div>
                @endforeach
            </div>
            <button
                class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-warning text-warning-foreground hover:bg-warning/90 h-10 px-4 py-2 btn-red text-md">
                {{ __('welcome.showMoreLanguages') }}
            </button>
        </div>
    </section>


    {{-- <div class="container-fluid py-5 px-4 px-md-5">
        <h2 class="text-center fw-bold mb-5">
            How It All <span class="text-warning">Works</span>
        </h2>
        <div class="row align-items-center">
            <!-- Left Column -->
            <div class="col-lg-4 mb-4">
                <h4 class="fw-bold text-warning">Simple Steps to Fluency</h4>
                <p class="text-secondary">
                    Learning a new language with FluentAll is designed to be easy, effective, and enjoyable.
                    Follow our straightforward process to connect with expert tutors and start your learning adventure.
                </p>
            </div>
            <!-- Center Image -->
            <div class="col-lg-4 mb-4 text-center">
                <div class="circle-img shadow">
                    <img src="{{ asset('assets/website/images/img2.jpeg') }}" alt="Tutor" />
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-lg-4">
                <div class="step-box d-flex">
                    <div class="step-number">1</div>
                    <div>
                        <h5 class="fw-bold">Find the Perfect Tutor</h5>
                        <p>Browse profiles, watch intros, and choose a tutor that matches your learning style.</p>
                    </div>
                </div>
                <div class="step-box d-flex">
                    <div class="step-number">2</div>
                    <div>
                        <h5 class="fw-bold">Schedule Your Lesson</h5>
                        <p>Pick a time that works for you and book your session with ease.</p>
                    </div>
                </div>
                <div class="step-box d-flex">
                    <div class="step-number">3</div>
                    <div>
                        <h5 class="fw-bold">Start Your Journey</h5>
                        <p>Connect with your tutor via video call and begin your personalized language lessons.</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <section class="py-16 md:py-20 bg-background">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-16 text-center"
                style="opacity: 1; transform: none;">{{ __('welcome.howItAll') }} <span
                    class="text-gradient-yellow-red">{{ __('welcome.works') }}</span></h2>
            <div class="grid md:grid-cols-3 gap-10 items-center">
                <div class="md:col-span-1" style="opacity: 1; transform: none;">
                    <h3 class="text-2xl font-semibold text-warning mb-4">{{ __('welcome.simpleStepstoFluency') }}</h3>
                    <p class="text-muted-foreground leading-relaxed">{{ __('welcome.simpleStepsSubHeading') }}</p>
                </div>
                <div class="md:col-span-1 flex justify-center" style="opacity: 1; transform: none;"><img
                        class="rounded-full object-cover w-56 h-56 md:w-72 md:h-72 shadow-xl border-4 border-secondary/50"
                        alt="Student focused on learning with FluentAll"
                        src="https://images.unsplash.com/photo-1611680580904-7be8bb7a5e88"></div>
                <div class="md:col-span-1 space-y-6" style="opacity: 1; transform: none;">
                    <div class="flex items-start p-4 rounded-lg border border-primary/20 hover:shadow-md transition-shadow">
                        <div
                            class="bg-gradient-to-br from-primary to-secondary text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg mr-4 shadow-sm flex-shrink-0">
                            1</div>
                        <div>
                            <h4 class="font-semibold text-lg text-foreground mb-1">{{ __('welcome.FindThePerfectTutor') }}
                            </h4>
                            <p class="text-muted-foreground text-sm">{{ __('welcome.perfectTutorSubHeading') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 rounded-lg border border-primary/20 hover:shadow-md transition-shadow">
                        <div
                            class="bg-gradient-to-br from-primary to-secondary text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg mr-4 shadow-sm flex-shrink-0">
                            2</div>
                        <div>
                            <h4 class="font-semibold text-lg text-foreground mb-1">{{ __('welcome.scheduleYourLesson') }}
                            </h4>
                            <p class="text-muted-foreground text-sm">{{ __('welcome.scheduleYourLessonSubHeading') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 rounded-lg border border-primary/20 hover:shadow-md transition-shadow">
                        <div
                            class="bg-gradient-to-br from-primary to-secondary text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg mr-4 shadow-sm flex-shrink-0">
                            3</div>
                        <div>
                            <h4 class="font-semibold text-lg text-foreground mb-1">{{ __('welcome.startYourJourney') }}
                            </h4>
                            <p class="text-muted-foreground text-sm">{{ __('welcome.startYourJourneySubHeading') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- <div class="container-fluid py-5 px-4 px-md-5" style="background-color: #F9FAFB;">
        <div class="row g-4">
            <!-- Left Column: Why Choose -->
            <div class="col-lg-6">
                <div class="card-custom h-100 p-5">
                    <h3 class="text-center"><span class="gradient-title">Why</span> Choose FluentAll?</h3>
                    <div class="mt-4 d-flex">
                        <div class="icon-box"><i class="bi bi-person-check"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Expert Tutors</h5>
                            <p class="text-secondary mb-0">Learn from certified native speakers and experienced
                                language professionals dedicated to your success.</p>
                        </div>
                    </div>
                    <div class="mt-4 d-flex">
                        <div class="icon-box"><i class="bi bi-bullseye"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Personalized Learning</h5>
                            <p class="text-secondary mb-0">Tailored lesson plans that adapt to your pace, goals, and
                                learning preferences for maximum effectiveness.</p>
                        </div>
                    </div>
                    <div class="mt-4 d-flex">
                        <div class="icon-box"><i class="bi bi-lightning-charge"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Flexible Scheduling</h5>
                            <p class="text-secondary mb-0">Find lessons that fit your busy life. Learn anytime,
                                anywhere, at your convenience.</p>
                        </div>
                    </div>
                    <div class="mt-4 d-flex">
                        <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Trusted Platform</h5>
                            <p class="text-secondary mb-0">Secure payments, verified tutors, and a supportive community
                                to ensure a safe and positive learning experience.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: About -->
            <div class="col-lg-6">
                <div class="card-custom h-100 p-5">
                    <h3 class="text-center"><span class="gradient-title">About</span> FluentAll</h3>
                    <p class="text-secondary mt-3">
                        FluentAll was founded on a simple mission: "Be fluent in all Languages you want".
                        We believe that learning a new language opens up a world of opportunities,
                        fostering understanding, connection, and personal growth.
                    </p>
                    <p class="text-secondary">
                        Our platform connects passionate learners with dedicated tutors from across the globe.
                        We're committed to creating an engaging, supportive, and effective learning environment.
                        Whether you're learning for travel, career, or personal enrichment, FluentAll is your partner in
                        achieving fluency.
                    </p>
                    <p class="text-secondary">
                        Join our community and embark on an exciting linguistic adventure. We're more than just a
                        platform; we're a global family of language enthusiasts.
                    </p>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-custom px-4 py-2">Learn More About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <section class="py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-10 md:gap-12">
                <div class="border border-primary/30 p-6 md:p-8 rounded-xl shadow-lg bg-white" id="why-us"
                    style="opacity: 1; transform: none;">
                    <h3 class="text-2xl md:text-3xl font-bold text-foreground mb-6 text-center"><span
                            class="text-gradient-yellow-red">{{ __('welcome.why') }}</span>
                        {{ __('welcome.chooseFluentAll') }}</h3>
                    <div class="space-y-5">
                        <div class="flex items-start"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="h-7 w-7 text-warning mr-4 flex-shrink-0 mt-1">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <div>
                                <h4 class="text-lg font-semibold text-foreground mb-1">{{ __('welcome.expertTutors') }}
                                </h4>
                                <p class="text-muted-foreground text-sm leading-relaxed">
                                    {{ __('welcome.expertTutorsHeading') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="h-7 w-7 text-warning mr-4 flex-shrink-0 mt-1">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                            <div>
                                <h4 class="text-lg font-semibold text-foreground mb-1">
                                    {{ __('welcome.personalizedLearning') }}</h4>
                                <p class="text-muted-foreground text-sm leading-relaxed">
                                    {{ __('welcome.personalizedLearningHeading') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="h-7 w-7 text-warning mr-4 flex-shrink-0 mt-1">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                            <div>
                                <h4 class="text-lg font-semibold text-foreground mb-1">
                                    {{ __('welcome.flexibleScheduling') }}</h4>
                                <p class="text-muted-foreground text-sm leading-relaxed">
                                    {{ __('welcome.flexibleSchedulingHeading') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="h-7 w-7 text-warning mr-4 flex-shrink-0 mt-1">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <div>
                                <h4 class="text-lg font-semibold text-foreground mb-1">{{ __('welcome.trustedPlatform') }}
                                </h4>
                                <p class="text-muted-foreground text-sm leading-relaxed">
                                    {{ __('welcome.trustedPlatformHeading') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border border-primary/30 p-6 md:p-8 rounded-xl shadow-lg bg-white" id="about-us-section"
                    style="opacity: 1; transform: none;">
                    <h3 class="text-2xl md:text-3xl font-bold text-foreground mb-6 text-center"><span
                            class="text-gradient-yellow-red">{{ __('welcome.aboutFluent') }}</span>
                        {{ __('welcome.fluentAll') }}</h3>
                    <div class="space-y-4 text-muted-foreground leading-relaxed text-sm">
                        <p>{{ __('welcome.aboutFluentP1') }}</p>
                        <p>{{ __('welcome.aboutFluentP2') }}</p>
                        <p>{{ __('welcome.aboutFluentP3') }}</p>
                    </div><button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-warning text-warning-foreground hover:bg-warning/90 h-10 px-4 py-2 mt-8 w-full btn-red">{{ __('welcome.learnMoreAboutUs') }}</button>
                </div>
            </div>
        </div>
    </section>



    <div class="container-fluid py-5 px-4 px-md-5">
        <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-12 text-center" style="opacity: 1; transform: none;">
            {{ __('welcome.our') }} <span class="text-gradient-yellow-red">{{ __('welcome.successStories') }}</span></h2>
        <div class="position-relative">
            <!-- Arrows -->
            <button class="arrow-btn position-absolute top-0 start-0 mt-2 ms-2" onclick="scrollTestimonials('left')">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button class="arrow-btn position-absolute top-0 end-0 mt-2 me-2" onclick="scrollTestimonials('right')">
                <i class="bi bi-arrow-right"></i>
            </button>

            <!-- Scrollable Testimonials -->
            <div id="testimonialScroll" class="carousel-inner-scroll">
                <!-- Card 1 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=1" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Maria S.</strong><br />
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>FluentAll helped me prepare for Italy.
                        Excellent tutor!</p>
                </div>

                <!-- Card 2 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=2" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>John B.</strong><br />
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>Perfect for Business English. Lessons
                        were customized.</p>
                </div>

                <!-- Card 3 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=3" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Aisha K.</strong><br />
                            <span class="text-warning">★★★★☆</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>Arabic became easy with a great tutor.
                        Shukran!</p>
                </div>

                <!-- Card 4 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=4" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Kenji T.</strong><br />
                            <span class="text-warning">★★★★½</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>Scheduling was very flexible. Highly
                        recommend!</p>
                </div>

                <!-- Card 5 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=5" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Sara M.</strong><br />
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>French lessons were super practical.
                        Merci!</p>
                </div>

                <!-- Card 6 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=6" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Liam R.</strong><br />
                            <span class="text-warning">★★★★☆</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>Improved my Spanish quickly. Tutor was
                        friendly.</p>
                </div>

                <!-- Card 7 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=7" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Ahmed Y.</strong><br />
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>German made easy. Sehr gut experience!
                    </p>
                </div>

                <!-- Card 8 -->
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://i.pravatar.cc/50?img=8" class="testimonial-img me-2" alt="">
                        <div>
                            <strong>Emily W.</strong><br />
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                    <p><i class="bi bi-chat-left-quote text-warning me-1"></i>Learned travel phrases fast. Gained
                        confidence!</p>
                </div>
            </div>
        </div>
    </div>


@endsection
