@extends('website.master.master')
@section('title', 'Home - FluentAll')
@section('content')
 
    <div class="container d-flex justify-content-center align-items-center min-vh-100 text-center">
        <div>
            <!-- Image -->
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/website/images/hero-image.jpeg') }}"
                    class="img-fluid rounded-3 shadow border border-warning border-3"
                    style="height: 260px; width: 100%; object-fit: cover; max-width: 650px;" alt="Online Languages">
            </div>
            <!-- Heading -->
            <h1 class="fw-bold mt-4">
                Start your journey and learn with <br>
                <span class="text-warning">best</span>
                <span style="color: orange;">online</span>
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
    </div>
    <div class="container-fluid" style="background-color:#f8f9fa;">
        <div class="container py-5">
            <h2 class="text-center fw-bold mb-2">Explore Languages</h2>
            <p class="text-center text-muted mb-5">Choose your path to fluency. We offer a wide range of languages
                taught by expert tutors.</p>
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
    </div>
    <div class="container-fluid py-5 px-4 px-md-5">
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
    </div>
    <div class="container-fluid py-5 px-4 px-md-5" style="background-color: #F9FAFB;">
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
    </div>



    <div class="container-fluid py-5 px-4 px-md-5">
        <h3 class="text-center mb-4">Our <span class="text-warning">Success Stories</span></h3>

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
