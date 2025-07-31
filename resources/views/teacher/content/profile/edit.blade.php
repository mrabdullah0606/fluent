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
                    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200 mb-8"
                        style="opacity: 1; transform: none;">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="flex-shrink-0 relative">
                                <span
                                    class="relative flex shrink-0 overflow-hidden rounded-full w-28 h-28 border-4 border-primary"><span
                                        class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ substr($teacher->name, 0, 2) }}</span></span>
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 absolute -bottom-2 -right-2 bg-secondary text-secondary-foreground p-1.5 rounded-full cursor-pointer hover:bg-secondary/90 transition-colors"
                                    for="image-upload">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg></label><input type="file"
                                    class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 hidden"
                                    id="image-upload" accept="image/*">
                            </div>
                            <div class="flex-grow text-center sm:text-left"><label
                                    class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-medium text-muted-foreground"
                                    for="name">Full Name</label>
                                <input
                                    class="flex h-10 w-full border-input bg-background py-2 ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-2xl font-bold border-0 border-b-2 rounded-none shadow-none px-1 focus-visible:ring-0 focus:border-primary"
                                    id="name" name="name" value="{{ old('name', $teacher->name) }}" type="text">
                            </div>
                        </div>
                    </div>
                    {{-- Headline --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="headline" class="block text-lg font-semibold mb-2">Headline</label>
                    <input type="text" id="headline" name="headline" class="form-control rounded-md" placeholder="E.g., Passionate English Tutor with 5+ Years Experience" value="{{ old('headline', $teacher?->headline) }}">
                    </div>

                    {{-- About Me --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="about_me" class="block text-lg font-semibold mb-2">About Me</label>
                    <textarea id="about_me" name="about_me" class="form-control rounded-md" rows="4" placeholder="Tell students a little about yourself...">{{ old('about_me', $teacher?->about_me) }}</textarea>
                    </div>

                    {{-- Languages You Teach --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="teaches" class="block text-lg font-semibold mb-2">Languages You Teach</label>
                    <input type="text" id="teaches" name="teaches" class="form-control rounded-md" placeholder="E.g., English, Spanish" value="{{ old('teaches', $teacher?->teaches) }}">
                    </div>

                    {{-- Languages You Speak --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="speaks" class="block text-lg font-semibold mb-2">Languages You Speak</label>
                    <input type="text" id="speaks" name="speaks" class="form-control rounded-md" placeholder="E.g., English, Urdu, Arabic" value="{{ old('speaks', $teacher?->speaks) }}">
                    </div>

                    {{-- Country --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="country" class="block text-lg font-semibold mb-2">Country</label>
                    <input type="text" id="country" name="country" class="form-control rounded-md" placeholder="E.g., Pakistan" value="{{ old('country', $teacher?->country) }}">
                    </div>

                    {{-- Rate per Hour --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="rate_per_hour" class="block text-lg font-semibold mb-2">Rate per Hour ($)</label>
                    <input type="number" step="0.01" id="rate_per_hour" name="rate_per_hour" class="form-control rounded-md" value="{{ old('rate_per_hour', $teacher?->rate_per_hour) }}">
                    </div>

                    {{-- Hobbies --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="hobbies" class="block text-lg font-semibold mb-2">Hobbies</label>
                    <input type="text" id="hobbies" name="hobbies" class="form-control rounded-md" placeholder="E.g., Reading, Traveling" value="{{ old('hobbies', $teacher?->hobbies) }}">
                    </div>

                    {{-- Certifications --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="certifications" class="block text-lg font-semibold mb-2">Certifications</label>
                    <input type="text" id="certifications" name="certifications" class="form-control rounded-md" placeholder="E.g., TEFL, CELTA" value="{{ old('certifications', $teacher?->certifications) }}">
                    </div>

                    {{-- Experience --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="experience" class="block text-lg font-semibold mb-2">Experience</label>
                    <input type="text" id="experience" name="experience" class="form-control rounded-md" placeholder="E.g., 5 years" value="{{ old('experience', $teacher?->experience) }}">
                    </div>

                    {{-- Teaching Style --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                    <label for="teaching_style" class="block text-lg font-semibold mb-2">Teaching Style</label>
                    <input type="text" id="teaching_style" name="teaching_style" class="form-control rounded-md" placeholder="E.g., Conversational, Grammar-Focused" value="{{ old('teaching_style', $teacher?->teaching_style) }}">
                    </div>

                    {{-- Save Button --}}
                    <div class="text-end mt-4">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 py-2">
                    <i class="bi bi-save me-1"></i> Save Profile
                    </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
@endsection
