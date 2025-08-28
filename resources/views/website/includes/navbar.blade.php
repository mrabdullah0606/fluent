@auth
    @if (auth()->user()->role === 'student')
        {{-- Student Navbar --}}
        <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3 py-3 navbar-bottom-border">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-nowrap">
                <!-- Left Logo & Title -->
                <a href="{{ route('student.dashboard') }}" style="text-decoration: none;cursor: pointer;">
                    <div class="d-flex align-items-center flex-nowrap overflow-hidden">
                        <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                            style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                            <i class="bi bi-book fs-2 text-white"></i>
                        </div>
                        <!-- Responsive text -->
                    </div>
                </a>
                <!-- Toggler stays inline -->
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar menu -->
                <div class="collapse navbar-collapse justify-content-between" id="navbarMenu">
                    <ul class="navbar-nav ms-3 mb-2 mb-lg-0">
                        <li class="nav-item me-3">
                            <a class="nav-link active d-flex align-items-center" href="{{ route('student.dashboard') }}">
                                <i class="bi bi-house me-1"></i> Home
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center" href="{{ route('student.chats.index') }}"><i
                                    class="bi bi-chat-left-text me-1"></i> Messages</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center" href="{{ route('student.lesson.tracking') }}"><i
                                    class="bi bi-camera-video me-1"></i> Meetings</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center gap-2">
                        <a class="btn btn-danger" href="{{ route('find.tutor') }}">
                            Find Tutor
                        </a>

                        <div class="dropdown">
                            <div class="dropdown-toggle rounded-circle border border-warning text-warning fw-bold d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px; cursor: pointer;" data-bs-toggle="dropdown"
                                aria-expanded="false" tabindex="0" role="button">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('student.profile.edit') }}">View Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @else
        {{-- Teacher or other authenticated users --}}
        <nav class="navbar navbar-expand-lg bg-white border-bottom border-warning border-2 py-3">
            <div
                class="container-fluid px-3 px-md-5 d-flex align-items-center justify-content-between flex-wrap flex-lg-nowrap">

                <!-- Left: Logo + Tagline -->
                <div class="d-flex align-items-center me-2 flex-shrink-1" style="max-width: 75%;">
                    <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                        style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                        <i class="bi bi-book fs-2 text-white"></i>
                    </div>

                    <div class="d-flex flex-column">
                        <a href="{{ route('index') }}">
                            <span class="fw-bold text-warning fs-5">FluentAll</span>
                        </a>
                        <small class="text-dark lh-sm d-block responsive-wrap" style="white-space: nowrap;">
                            Be fluent in all Languages you want
                        </small>
                    </div>
                </div>

                <!-- Toggle Button -->
                <button class="navbar-toggler mt-2 mt-lg-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation -->
                <div class="collapse navbar-collapse justify-content-end align-items-center mt-2 mt-lg-0 w-100"
                    id="navbarNav">
                    @php
                        $user = auth()->user();
                    @endphp

                    @if ($user->role !== 'student')
                        <a href="{{ route('switch.to.teacher') }}"
                            class="btn btn-outline-warning px-4 py-2 fw-semibold me-3">
                            🔁 Switch to Teacher Account
                        </a>
                    @endif

                    <ul class="navbar-nav me-3">
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('index') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('messages') }}">Messages</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('contact') }}">Contact Us</a>
                        </li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('about') }}">About</a></li>
                    </ul>

                    <a href="{{ route('find.tutor') }}" class="btn btn-sm px-4 py-2 fw-semibold text-white me-2"
                        style="background-color: #E83030;">Find Tutor</a>

                    @php
                        $initial = strtoupper(substr($user->name, 0, 1));
                    @endphp

                    {{-- Teacher Dropdown --}}
                    @if ($user->role === 'teacher')
                        <div class="dropdown">
                            <div class="dropdown-toggle rounded-circle border border-warning text-warning fw-bold d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px; cursor: pointer;" data-bs-toggle="dropdown"
                                aria-expanded="false" tabindex="0" role="button">
                                {{ $initial }}
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('teacher.profile.edit') }}">View Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('teacher.settings.index') }}">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-teacher').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form-teacher" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        {{-- For other authenticated users (not student, not teacher) --}}
                        <div class="dropdown">
                            <div class="dropdown-toggle rounded-circle border border-warning text-warning fw-bold d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px; cursor: pointer;" data-bs-toggle="dropdown"
                                aria-expanded="false" tabindex="0" role="button">
                                {{ $initial }}
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-other').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form-other" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    @endif
@else
    {{-- Not authenticated - Guest navbar --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom border-warning border-2 py-3">
        <div
            class="container-fluid px-3 px-md-5 d-flex align-items-center justify-content-between flex-wrap flex-lg-nowrap">

            <!-- Left: Logo + Tagline -->
            <div class="d-flex align-items-center me-2 flex-shrink-1" style="max-width: 75%;">
                <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                    style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                    <i class="bi bi-book fs-2 text-white"></i>
                </div>

                <div class="d-flex flex-column">
                    <a href="{{ route('index') }}">
                        <span class="fw-bold text-warning fs-5">FluentAll</span>
                    </a>
                    <small class="text-dark lh-sm d-block responsive-wrap" style="white-space: nowrap;">
                        Be fluent in all Languages you want
                    </small>
                </div>
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler mt-2 mt-lg-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation -->
            <div class="collapse navbar-collapse justify-content-end align-items-center mt-2 mt-lg-0 w-100"
                id="navbarNav">
                <a href="{{ route('switch.to.teacher') }}" class="btn btn-outline-warning px-4 py-2 fw-semibold me-3">
                    🔁 Switch to Teacher Account
                </a>

                <ul class="navbar-nav me-3">
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('messages') }}">Messages</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('about') }}">About</a></li>
                </ul>

                <a href="{{ route('find.tutor') }}" class="btn btn-sm px-4 py-2 fw-semibold text-white me-2"
                    style="background-color: #E83030;">Find Tutor</a>

                {{-- Not logged in - Show login button --}}
                <a href="{{ route('student.login') }}" class="btn btn-outline-danger px-4 py-2 fw-semibold btn-sm">
                    Student Log In
                </a>
            </div>
        </div>
    </nav>
@endauth
