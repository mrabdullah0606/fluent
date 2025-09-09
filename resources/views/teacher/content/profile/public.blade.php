@extends('teacher.master.master')
@section('title', 'Public Profile - FluentAll')
@section('content')
    {{-- @push('teacherStyles')
    <style>
        .nav-link.active {
            background-color: #fff3cd !important;
            border-radius: 8px;
        }

        .nav-link:hover {
            background-color: #f5f5f5 !important;
            border-radius: 8px;
        }

        .btn-switch {
            border: 1px solid #fdbd00;
            color: #fdbd00;
            font-weight: 500;
        }

        .btn-switch:hover {
            background-color: #FFBF00;
        }

        .nav-text-orange {
            color: #ffae00;
            font-weight: 600;
        }

        .navbar-bottom-border {
            border-bottom: 1px solid #fdbd00;
        }

        .navbar-brand.nav-text-orange:hover {
            color: #ffae00;
            /* same as default */
            text-decoration: none;
            cursor: default;
        }

        .preview-bar {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px 20px;
            border-left: 5px solid #ffc107;
        }

        .profile-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .initials {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #ffc107;
            color: #fff;
            font-size: 36px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .highlight-yellow {
            color: #ffc107;
        }

        .active-badge {
            background-color: #28a745;
            color: white;
            font-size: 14px;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .btn-message {
            border: 1px solid #ffc107;
            color: #ffc107;
        }

        @media (max-width: 768px) {
            .flex-md-row {
                flex-direction: column !important;
                text-align: center;
            }

            .initials {
                margin: 0 auto 20px;
            }
        }

        .icon-heading {
            font-size: 24px;
            font-weight: bold;
        }

        .yellow-icon {
            color: #ffc107;
        }

        .lesson-card {
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .lesson-title {
            color: #ffc107;
            font-weight: bold;
        }

        .badge-specialty {
            background-color: #dc3545;
            color: #fff;
            font-size: 14px;
            padding: 5px 12px;
            border-radius: 20px;
            margin-right: 5px;
        }

        .select-btn {
            background-color: #ffe082;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            color: #444;
            border-radius: 8px;
        }

        .review-box {
            border-radius: 12px;
            border: 1px solid #eee;
            padding: 16px;
            background-color: #fff;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #f2f2f2;
            color: #555;
            border-radius: 50%;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .review-stars i {
            color: #ffc107;
        }

        .review-text {
            font-style: italic;
            color: #444;
        }

        .btn-outline-secondary:hover {
            background-color: rgb(255, 242, 204) !important;
            color: #000 !important;
            /* optional: for better contrast */
            border-color: rgb(255, 242, 204) !important;
        }
    </style>
    @endpush --}}
    {{-- <div class="container-fluid px-3 px-md-4 px-lg-5 py-5">
        <div class="preview-bar">
            👁️ This is a preview of how your profile appears to students.
            <a href="{{ route('teacher.profile.edit') }}" class="text-dark fw-semibold text-decoration-underline ms-1">Edit
                Profile</a>
        </div>
    </div>

    <!-- Profile Container -->
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="profile-card row align-items-center">

            <!-- Left Column: Circle + Name + Desc -->
            <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <div class="initials mb-3">{{ substr($teacher->name, 0, 2) }}</div>
                    <h3><strong>{{ $teacher->name }}</strong></h3>
                    <p class="highlight-yellow mb-1 text-center text-md-start">
                        Passionate TEFL Certified English Tutor |<br>
                        Conversational & Business English Expert
                    </p>
                    <div class="mb-2">
                        ⭐ <strong>4.9</strong> (180 reviewss)
                    </div>
                    <span class="active-badge">Active Now</span>
                </div>
            </div>

            <!-- Right Column: Info Boxes + Buttons -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-6 mb-2">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i> From: US United States
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-person-workspace text-warning me-1"></i> Teaches: English
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-translate text-warning me-1"></i> Speaks: English (Native), Spanish (B2)
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-mortarboard-fill text-warning me-1"></i> Experience: 5+ years
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-cash-coin text-warning me-1"></i> Rate: $28/hour
                    </div>

                </div>

                <div class="mt-3 d-flex flex-wrap gap-2 justify-content-start">
                    <button class="btn btn-danger px-4">📅 Book Lesson</button>
                    <button class="btn btn-message px-4">💬 Message Tutor</button>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid px-3 px-md-4 px-lg-5 py-5">
        <div class="row g-4">

            <!-- Left Column -->
            <div class="col-lg-8">

                <!-- About Me -->
                <div class="bg-white rounded shadow-sm p-4 mb-4">
                    <div class="icon-heading mb-2">
                        <span class="yellow-icon">📖</span> About Me
                    </div>
                    <p>
                        Hello! I'm Sarah, a TEFL-certified English tutor with over 5 years of experience helping
                        students worldwide achieve their language goals. My lessons are dynamic, interactive, and
                        tailored to your specific needs, whether you're looking to improve your conversational skills,
                        master business English, or prepare for an exam. I believe in creating a supportive and fun
                        learning environment where you feel comfortable to practice and grow. Let's embark on this
                        language journey together!
                    </p>
                </div>

                <!-- Specialties -->
                <div class="bg-white rounded shadow-sm p-4">
                    <div class="icon-heading mb-2">
                        <span class="yellow-icon">🏅</span> Specialtiess
                    </div>
                    <div class="d-flex flex-wrap">
                        <span class="badge-specialty">Conversational English</span>
                        <span class="badge-specialty">Business English</span>
                        <span class="badge-specialty">IELTS Prep</span>
                        <span class="badge-specialty">Pronunciation</span>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-lg-4">

                <div class="bg-white rounded shadow-sm p-4">
                    <div class="icon-heading mb-3">
                        <span class="yellow-icon">👥</span> Lesson Packages
                    </div>

                    <!-- Package 1 -->
                    <div class="lesson-card">
                        <div class="lesson-title">Basic English (5 Lessons)</div>
                        <h4 class="mt-2">$100</h4>
                        <ul class="list-unstyled mb-3">
                            <li>✅ Personalized curriculum</li>
                            <li>✅ Homework assignments</li>
                            <li>✅ Progress tracking</li>
                        </ul>
                        <button class="select-btn">Select Package</button>
                    </div>

                    <!-- Package 2 -->
                    <div class="lesson-card">
                        <div class="lesson-title">Business English Pro (10 Lessons)</div>
                        <h4 class="mt-2">$250</h4>
                        <ul class="list-unstyled mb-3">
                            <li>✅ Industry-specific vocabulary</li>
                            <li>✅ Meeting & presentation skills</li>
                            <li>✅ Email & report writing</li>
                        </ul>
                        <button class="select-btn">Select Package</button>
                    </div>

                    <!-- ✅ Extra Package Below -->
                    <div class="lesson-card">
                        <div class="lesson-title">Fluency Accelerator (20 Lessons)</div>
                        <h4 class="mt-2">$450</h4>
                        <ul class="list-unstyled mb-3">
                            <li>✅ Intensive speaking practice</li>
                            <li>✅ Cultural immersion topics</li>
                            <li>✅ Advanced grammar focus</li>
                        </ul>
                        <button class="select-btn">Select Package</button>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="bg-white p-4 rounded shadow-sm">
            <h5 class="mb-4">
                <i class="bi bi-star-fill text-warning me-1"></i>
                <strong>Student Reviews (180)</strong>
            </h5>

            <div class="row g-3">
                <!-- Review Card -->
                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">M</div>
                                <div>
                                    <strong>Maria K.</strong><br>
                                    <small class="text-muted">June 10, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <div class="review-text">"Sarah is an amazing teacher! My confidence in speaking English has
                            improved so much."</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">K</div>
                                <div>
                                    <strong>Kenji T.</strong><br>
                                    <small class="text-muted">June 8, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <div class="review-text">"Excellent for business English. Very professional and helpful."</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">L</div>
                                <div>
                                    <strong>Linda S.</strong><br>
                                    <small class="text-muted">June 5, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="review-text">"Good lessons, very patient."</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">A</div>
                                <div>
                                    <strong>Ahmed R.</strong><br>
                                    <small class="text-muted">June 2, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <div class="review-text">"Sarah’s methods are very effective. I learned a lot in a short time."
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">C</div>
                                <div>
                                    <strong>Chloe B.</strong><br>
                                    <small class="text-muted">May 28, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="review-text">"Highly recommend! She makes learning fun and engaging."</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="review-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">D</div>
                                <div>
                                    <strong>David P.</strong><br>
                                    <small class="text-muted">May 25, 2025</small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="review-text">"Very knowledgeable and supportive. Helped me with my IELTS."</div>
                    </div>
                </div>
            </div>

            <!-- Show More Button -->
            <div class="text-center mt-4">
                <button class="btn btn-outline-secondary">Show More Reviews (2 more)</button>
            </div>
        </div>
    </div> --}}
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
                            <!-- Profile Image -->
                            <span
                                class="relative flex shrink-0 rounded-full w-32 h-32 md:w-40 md:h-40 mb-4 border-4 border-yellow-400 cursor-pointer">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-full text-4xl bg-yellow-400 text-white overflow-hidden">
                                    @if ($teacher && $teacher->profile_image)
                                        <img src="{{ asset('storage/' . $teacher->profile_image) }}"
                                            class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span
                                            class="flex w-full h-full items-center justify-center rounded-full text-4xl text-white">
                                            <span style="font-size: 12px;">No Image Uploaded</span>
                                        </span>
                                    @endif
                                </span>
                            </span>

                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                                {{ $user->name ?? 'Unnamed Teacher' }}
                            </h1>
                            <p class="text-primary text-md mt-1">{{ $teacher?->headline ?? 'No headline added yet' }}</p>

                            <!-- Ratings -->
                            <div class="flex items-center mt-2 text-gray-700">
                                @php
                                    $fullStars = floor($averageRating);
                                    $halfStar = ($averageRating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                {{-- Full Stars --}}
                                @for ($i = 0; $i < $fullStars; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 fill-yellow-500 mr-1"
                                            viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14
                                    18.18 21.02 12 17.77 5.82 21.02
                                    7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                @endfor

                                {{-- Half Star --}}
                                @if ($halfStar)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1"
                                            viewBox="0 0 24 24">
                                            <defs>
                                                <linearGradient id="half-grad">
                                                    <stop offset="50%" stop-color="currentColor" />
                                                    <stop offset="50%" stop-color="transparent" />
                                                </linearGradient>
                                            </defs>
                                            <polygon fill="url(#half-grad)" stroke="currentColor" stroke-width="2" points="12 2 15.09 8.26 22 9.27 17 14.14
                                    18.18 21.02 12 17.77 5.82 21.02
                                    7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                @endif

                                {{-- Empty Stars --}}
                                @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 mr-1"
                                            viewBox="0 0 24 24">
                                            <polygon fill="none" stroke="currentColor" stroke-width="2" points="12 2 15.09 8.26 22 9.27 17 14.14
                                    18.18 21.02 12 17.77 5.82 21.02
                                    7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                @endfor

                                {{-- Rating & Review Count --}}
                                <div class="flex items-center ml-2">
                                    @if ($reviewsCount > 0)
                                        <span class="font-semibold text-yellow-500">{{ $averageRating }}</span>
                                        <span class="ml-1 text-gray-600">
                                            ({{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }})
                                        </span>
                                    @else
                                        <span class="text-gray-500">No ratings yet</span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent mt-3 bg-green-500 hover:bg-green-600 text-white">
                                Active Now
                            </div>
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
                                        From: {{ $teacher?->country ?? 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m5 8 6 6"></path>
                                            <path d="m4 14 6-6 2-3"></path>
                                            <path d="M2 5h12"></path>
                                            <path d="M7 2h1"></path>
                                            <path d="m22 22-5-10-5 10"></path>
                                            <path d="M14 18h6"></path>
                                        </svg>
                                        @php
                                            use App\Models\Language;
                                            $languageNames = Language::whereIn('id', (array) $teacher->teaches)
                                                ->pluck('name')
                                                ->toArray();
                                        @endphp
                                        Teaches: {{ implode(', ', $languageNames) ?: 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="6"></circle>
                                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                        </svg>
                                        Experience: {{ $teacher?->experience ?? 'N/A' }}
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
                                        Speaks: {{ $teacher?->speaks ?? 'N/A' }}
                                    </div>

                                    <div class="flex items-center text-gray-700 col-span-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 mr-2 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Rate: ${{ $settings['duration_60'] ?? '0.00' }}/hour
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
                                                                <!-- YouTube Embed
                                        <iframe width="100%" height="315"
                                            src="https://www.youtube.com/embed/AbkEmIgJMcU?si=2NiijR0Ia01GfgRN"
                                            title="YouTube video player"
                                            frameborder="0"
                                            class="w-full h-48 md:h-64 rounded-lg shadow-md"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allowfullscreen>
                                        </iframe> -->
                                    @endif
                                </div>

                            </div>

                            <!-- Action Buttons under both -->
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                                <button
                                    class="inline-flex items-center justify-center text-sm h-10 w-full sm:w-auto bg-red-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md"
                                    disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2 h-5 w-5">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"> </rect>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2 h-5 w-5">
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
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg> About Me</h2>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                    {{ $teacher?->about_me ?? 'No information provided yet.' }}
                                </p>
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                                style="opacity: 1; transform: none;">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
                                        <circle cx="12" cy="8" r="6"></circle>
                                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                    </svg> Specialties</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $teacher?->teaching_style ?? '') as $style)
                                        @if(trim($style) != '')
                                            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 
                                            text-xs font-semibold transition-colors focus:outline-none 
                                            focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent 
                                            bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                {{ trim(ucwords($style)) }}
                                            </div>
                                        @endif
                                    @endforeach
                                    <!-- <div
                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                            Conversational English</div>
                                        <div
                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                            Business English</div>
                                        <div
                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                            IELTS Prep</div>
                                        <div
                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                            Pronunciation</div> -->
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200" style="opacity: 1; transform: none;">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round" class="mr-2 h-6 w-6 text-primary">
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

                    <div class="border border-red-300 p-4 rounded-lg bg-white hover:shadow-lg transition-shadow">
                        <h3 class="font-semibold text-red-600 text-lg">{{ $package->name }}</h3>

                        @if ($discountPercentage > 0)
                            <p class="text-sm font-bold text-green-600">Save {{ $discountPercentage }}%</p>
                        @endif

                        <div class="text-xs text-gray-600 mb-2">
                            <p><strong>{{ $package->number_of_lessons }}</strong> lessons</p>
                            <p>
                                <span class="font-semibold text-black text-base">
                                    ${{ number_format($originalPrice, 2) }}
                                </span>
                            </p>

                            @if ($package->duration_per_lesson)
                                <p><strong>{{ $package->duration_per_lesson }}</strong> minutes per lesson</p>
                            @endif
                        </div>

                        <ul class="text-xs text-gray-500 space-y-1 my-2">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="h-3 w-3 mr-2 text-green-500">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Flexible scheduling
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="h-3 w-3 mr-2 text-green-500">
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
                    <p class="text-gray-500 text-sm">No lesson packages available at the moment.</p>
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
        Student Reviews ({{ $reviews->count() }})
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($reviews as $review)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <span class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3">
                            <span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-gray-700">
                                {{ strtoupper(substr($review->student->name ?? 'S', 0, 1)) }}
                            </span>
                        </span>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $review->student->name ?? 'Anonymous' }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
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

                    <!--static data -->
                    <!--  <div class="bg-white p-6 md:p-8 rounded-xl shadow-md border border-gray-200 mt-12"
                            style="opacity: 1; transform: none;">
                            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6 flex items-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-3 h-7 w-7 text-primary">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg> Student Reviewss (180)</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">M</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Maria K.</p>
                                                <p class="text-xs text-gray-500">June 10, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Sarah is an amazing teacher! My confidence in
                                        speaking English has improved so much."</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">K</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Kenji T.</p>
                                                <p class="text-xs text-gray-500">June 8, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Excellent for business English. Very
                                        professional and helpful."</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">L</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Linda S.</p>
                                                <p class="text-xs text-gray-500">June 5, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Good lessons, very patient."</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">A</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Ahmed R.</p>
                                                <p class="text-xs text-gray-500">June 2, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Sarah's methods are very effective. I learned
                                        a lot in a short time."</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">C</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Chloe B.</p>
                                                <p class="text-xs text-gray-500">May 28, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Highly recommend! She makes learning fun and
                                        engaging."</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                    style="opacity: 1; transform: none;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center"><span
                                                class="relative flex shrink-0 overflow-hidden rounded-full w-10 h-10 mr-3"><span
                                                    class="flex h-full w-full items-center justify-center rounded-full bg-muted">D</span></span>
                                            <div>
                                                <p class="font-semibold text-gray-800">David P.</p>
                                                <p class="text-xs text-gray-500">May 25, 2025</p>
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
                                    <p class="text-sm text-gray-600 italic mt-3">"Very knowledgeable and supportive. Helped me
                                        with my IELTS."</p>
                                </div>
                            </div>
                            <div class="text-center mt-8"><button
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Show
                                    More Reviews (2 more)</button></div>
                        </div> -->
                </div>
            </div>
        </div>
    </main>
@endsection