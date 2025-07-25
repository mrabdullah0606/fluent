<nav class="navbar navbar-expand-lg bg-white border-bottom border-warning border-2 py-3">
    <div class="container-fluid px-3 px-md-5 d-flex align-items-center justify-content-between flex-wrap flex-lg-nowrap">

        <!-- Left: Logo + Tagline -->
        <div class="d-flex align-items-center me-2 flex-shrink-1" style="max-width: 75%;">
            <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                <i class="bi bi-book fs-2 text-white"></i>
            </div>

            <div class="d-flex flex-column">
                <span class="fw-bold text-warning fs-5">FluentAll</span>
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
        <div class="collapse navbar-collapse justify-content-end align-items-center mt-2 mt-lg-0 w-100" id="navbarNav">

            <a href="" class="btn btn-outline-warning px-4 py-2 fw-semibold me-3">
                🔁 Switch to Teacher Account
            </a>

            <ul class="navbar-nav me-3">
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('index') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('messages') }}">Messages</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('contact') }}">Contact Us</a>
                </li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('about') }}">About</a></li>
            </ul>

            <a href="{{ route('find.tutor') }}" class="btn btn-sm px-4 py-2 fw-semibold text-white me-2"
                style="background-color: #E83030;">Find Tutor</a>

            <a href="#" class="btn btn-outline-danger px-4 py-2 fw-semibold btn-sm">Student
                Log In</a>
        </div>
    </div>
</nav>
