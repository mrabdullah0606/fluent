@extends('student.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')

    <div class="container text-center my-5">
        <h1 class="fw-bold">Find Your Lesson</h1>
        <p class="text-muted">Discover the perfect learning experience tailored for you.</p>

        <div class="row justify-content-center mt-5 g-4">
            <!-- One-on-One Lessons -->
            <div class="col-md-5">
                <div class="border p-4 border-warning lesson-card h-100">
                    <div class="icon text-warning"><i class="bi bi-person"></i></div>
                    <h4 class="fw-bold">One-on-One Lessons</h4>
                    <p class="text-muted">Get personalized attention and a tailored learning plan with a dedicated
                        tutor. Perfect for focused learning and rapid progress.</p>
                    <a href="{{ route('student.one.on.one.tutors') }}" class="btn btn-danger mt-3">Find Private Tutors <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Group Lessons -->
            <div class="col-md-5">
                <div class="border p-4 border-warning lesson-card h-100">
                    <div class="icon text-danger"><i class="bi bi-people"></i></div>
                    <h4 class="fw-bold">Group Lessons</h4>
                    <p class="text-muted">Learn with peers in a collaborative environment. Interactive sessions that are
                        fun, engaging, and budget-friendly.</p>
                    <a href="{{ route('student.group.lesson') }}" class="btn btn-danger mt-3">Explore Group Lessons <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection
