@extends('teacher.master.master')
@section('title', 'Settings Profile - FluentAll')

@push('teacherStyles')
    <link rel="stylesheet" href="{{ asset('assets/website/css/teacher-settings.css') }}">
@endpush

@section('content')
    <main class="flex-grow">
        <div class="container my-4">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-gear-fill text-warning me-2"></i> Lesson Settings
            </h3>

            <div class="row">
                <!-- Sidebar Navigation -->
                @include('teacher.content.profile.particals.settings-sidebar')

                <!-- Main Content -->
                <div class="col-md-9">
                    <form action="{{ route('teacher.settings.update') }}" method="POST" id="settingsForm">
                        @csrf
                        @method('PUT')

                        <!-- Individual Lesson Pricing -->
                        @include('teacher.content.profile.particals.individual-pricing')

                        <!-- Lesson Packages -->
                        @include('teacher.content.profile.particals.lesson-packages')

                        <!-- Group Classes -->
                        @include('teacher.content.profile.particals.group-classes')

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-danger fw-bold px-4">
                                <i class="bi bi-save me-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/website/js/teacher-settings.js') }}"></script>
@endpush
