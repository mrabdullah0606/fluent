@extends('teacher.master.master')
@section('title', 'Profile Management - FluentAll')
@section('content')
    <style>

    </style>


    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto max-w-4xl">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8">Edit Your Teacher Profileee</h1>
                </div>
                <form action="{{ route('teacher.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        {{-- Headline --}}
                        <div class="mb-4">
                            <label for="headline" class="block text-lg font-semibold mb-2">Headline</label>
                            <input type="text" id="headline" name="headline" class="form-control rounded-md" placeholder="E.g., Passionate English Tutor with 5+ Years Experience" value="{{ old('headline', $teacher?->headline) }}">
                        </div>

                        {{-- About Me --}}
                        <div class="mb-4">
                            <label for="about_me" class="block text-lg font-semibold mb-2">About Me</label>
                            <textarea id="about_me" name="about_me" class="form-control rounded-md" rows="4" placeholder="Tell students a little about yourself...">{{ old('about_me', $teacher?->about_me) }}</textarea>
                        </div>

                        {{-- Languages You Teach --}}
                        <div class="mb-4">
                            <label for="teaches" class="block text-lg font-semibold mb-2">Languages You Teach</label>
                            <input type="text" id="teaches" name="teaches" class="form-control rounded-md" placeholder="E.g., English, Spanish" value="{{ old('teaches', $teacher?->teaches) }}">
                        </div>

                        {{-- Languages You Speak --}}
                        <div class="mb-4">
                            <label for="speaks" class="block text-lg font-semibold mb-2">Languages You Speak</label>
                            <input type="text" id="speaks" name="speaks" class="form-control rounded-md" placeholder="E.g., English, Urdu, Arabic" value="{{ old('speaks', $teacher?->speaks) }}">
                        </div>

                        {{-- Country --}}
                        <div class="mb-4">
                            <label for="country" class="block text-lg font-semibold mb-2">Country</label>
                            <input type="text" id="country" name="country" class="form-control rounded-md" placeholder="E.g., Pakistan" value="{{ old('country', $teacher?->country) }}">
                        </div>

                        {{-- Rate per Hour --}}
                        <div class="mb-4">
                            <label for="rate_per_hour" class="block text-lg font-semibold mb-2">Rate per Hour ($)</label>
                            <input type="number" step="0.01" id="rate_per_hour" name="rate_per_hour" class="form-control rounded-md" value="{{ old('rate_per_hour', $teacher?->rate_per_hour) }}">
                        </div>

                        {{-- Hobbies --}}
                        <div class="mb-4">
                            <label for="hobbies" class="block text-lg font-semibold mb-2">Hobbies</label>
                            <input type="text" id="hobbies" name="hobbies" class="form-control rounded-md" placeholder="E.g., Reading, Traveling" value="{{ old('hobbies', $teacher?->hobbies) }}">
                        </div>

                        {{-- Certifications --}}
                        <div class="mb-4">
                            <label for="certifications" class="block text-lg font-semibold mb-2">Certifications</label>
                            <input type="text" id="certifications" name="certifications" class="form-control rounded-md" placeholder="E.g., TEFL, CELTA" value="{{ old('certifications', $teacher?->certifications) }}">
                        </div>

                        {{-- Experience --}}
                        <div class="mb-4">
                            <label for="experience" class="block text-lg font-semibold mb-2">Experience</label>
                            <input type="text" id="experience" name="experience" class="form-control rounded-md" placeholder="E.g., 5 years" value="{{ old('experience', $teacher?->experience) }}">
                        </div>

                        {{-- Teaching Style --}}
                        <div class="mb-4">
                            <label for="teaching_style" class="block text-lg font-semibold mb-2">Teaching Style</label>
                            <input type="text" id="teaching_style" name="teaching_style" class="form-control rounded-md" placeholder="E.g., Conversational, Grammar-Focused" value="{{ old('teaching_style', $teacher?->teaching_style) }}">
                        </div>

                        {{-- Save Button --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 py-2">
                                <i class="bi bi-save me-1"></i> Save Profile
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </main>
@endsection
