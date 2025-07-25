@extends('website.master.master')
@section('title', 'Messages - FluentAll')
@section('content')
    <div class="container py-5">
        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold section-heading">About <span>fluentAll</span></h2>
            <p class="text-muted">
                We are passionate about breaking down language barriers and fostering a global community of <br>
                learners and educators.
                Our vision: <em>"Be fluent in all Languages you want"</em>.
            </p>
        </div>

        <!-- Content Box -->
        <div class="about-box">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h5 class="mission-title">Our Mission</h5>
                    <p class="text-muted">
                        To make high-quality language education accessible, engaging, and effective for everyone,
                        everywhere.
                        We strive to empower individuals by connecting them with expert tutors and fostering a love for
                        language learning.
                    </p>

                    <h5 class="vision-title mt-4">Our Vision</h5>
                    <p class="text-muted">
                        To be the leading global platform for language learning, helping everyone "Be fluent in all
                        Languages you want".
                        We are recognized for our commitment to student success, tutor excellence, and innovative
                        educational approaches.
                    </p>
                </div>

                <!-- Image -->
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('assets/website/images/hero-image.jpeg') }}" alt="Online Languages"
                        class="about-img">
                </div>
            </div>
        </div>
    </div>


    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-3">
                Why <span class="text-gradient">Choose fluentAll?</span>
            </h2>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-person-check-fill"></i></div>
                    <h6 class="fw-bold">Expert Tutors</h6>
                    <p class="text-small">Learn from certified native speakers and experienced language professionals.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-bullseye"></i></div>
                    <h6 class="fw-bold">Personalized Learning</h6>
                    <p class="text-small">Tailored lesson plans that adapt to your pace and goals.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-calendar-check-fill"></i></div>
                    <h6 class="fw-bold">Flexible Scheduling</h6>
                    <p class="text-small">Find lessons that fit your busy life. Learn anytime, anywhere.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-4 text-center">
                    <div class="icon mb-3"><i class="bi bi-shield-check"></i></div>
                    <h6 class="fw-bold">Trusted Platform</h6>
                    <p class="text-small">Secure payments, verified tutors, and a supportive community.</p>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold fs-3">
                OUR <span class="text-gradient">VALUES</span>
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-award-fill"></i></div>
                    <h6 class="fw-bold">Excellence</h6>
                    <p class="text-small">We uphold the highest standards in teaching and platform experience.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-globe-americas"></i></div>
                    <h6 class="fw-bold">Global Community</h6>
                    <p class="text-small">We foster connections and understanding across cultures.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4 text-center">
                    <div class="icon icon-red mb-3"><i class="bi bi-people-fill"></i></div>
                    <h6 class="fw-bold">Learner-Centric</h6>
                    <p class="text-small">Our students’ progress and satisfaction are our top priorities.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
