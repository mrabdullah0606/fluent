@extends('teacher.master.master')
@section('title', 'Profile Management - FluentAll')
@section('content')
    <style>

    </style>


    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto max-w-4xl">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8">Edit Your Teacher Profile</h1>
                </div>
                <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
                    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200 mb-8"
                        style="opacity: 1; transform: none;">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="flex-shrink-0 relative">
                                <span
                                    class="relative flex shrink-0 overflow-hidden rounded-full w-28 h-28 border-4 border-primary">
                                    <img id="imagePreview"
                                        src="{{ $teacher && $teacher->profile_image
                                            ? asset('storage/' . $teacher->profile_image)
                                            : 'https://www.w3schools.com/howto/img_avatar.png' }}"
                                        class="w-full h-full object-cover rounded-full">
                                </span>

                                {{-- Upload Button --}}
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 absolute -bottom-2 -right-2 bg-secondary text-secondary-foreground p-1.5 rounded-full cursor-pointer hover:bg-secondary/90 transition-colors"
                                    for="image-upload">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg>
                                </label>
                                <input type="file" name="profile_image" class="hidden" id="image-upload"
                                    accept="image/*">
                            </div>

                            <div class="flex-grow text-center sm:text-left"><label
                                    class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-medium text-muted-foreground float-left"
                                    for="name" style="float: left;margin-left: 3px;">Full Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                    class="w-full text-2xl font-bold px-1 py-2 form-control rounded-md border-b-2 border-gray-400 focus:border-blue-600 focus:outline-none">
                            </div>
                        </div>
                    </div>
                    {{-- Headline --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="headline" class="block text-lg font-semibold mb-2">Headline</label>
                        <input type="text" id="headline" name="headline" class="form-control rounded-md"
                            placeholder="E.g., Passionate English Tutor with 5+ Years Experience"
                            value="{{ old('headline', $teacher?->headline) }}">
                    </div>

                    {{-- About Me --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="about_me" class="block text-lg font-semibold mb-2">About Me</label>
                        <textarea id="about_me" name="about_me" class="form-control rounded-md" rows="4"
                            placeholder="Tell students a little about yourself...">{{ old('about_me', $teacher?->about_me) }}</textarea>
                    </div>

                    {{-- Languages You Teach --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="teaches" class="block text-lg font-semibold mb-2">Languages You Teach</label>
                        <select name="teaches[]" id="teaches" class="form-control rounded-md" multiple>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}"
                                    {{ in_array($language->id, old('teaches', $teacher?->teaches ?? [])) ? 'selected' : '' }}>
                                    {{ ucfirst($language->name) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple languages</p>
                    </div>

                    {{-- Languages You Speak --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="speaks" class="block text-lg font-semibold mb-2">Languages You Speak</label>
                        <input type="text" id="speaks" name="speaks" class="form-control rounded-md"
                            placeholder="E.g., English, Urdu, Arabic" value="{{ old('speaks', $teacher?->speaks) }}">
                    </div>

                    {{-- Country --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="country" class="block text-lg font-semibold mb-2">Country</label>
                        <input type="text" id="country" name="country" class="form-control rounded-md"
                            placeholder="E.g., Pakistan" value="{{ old('country', $teacher?->country) }}">
                    </div>

                    {{-- Rate per Hour --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="rate_per_hour" class="block text-lg font-semibold mb-2">Rate per Hour ($)</label>
                        <input type="number" step="0.01" id="rate_per_hour" name="rate_per_hour"
                            class="form-control rounded-md" value="{{ old('rate_per_hour', $teacher?->rate_per_hour) }}">
                    </div>

                    {{-- Hobbies --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="hobbies" class="block text-lg font-semibold mb-2">Hobbies</label>
                        <input type="text" id="hobbies" name="hobbies" class="form-control rounded-md"
                            placeholder="E.g., Reading, Traveling" value="{{ old('hobbies', $teacher?->hobbies) }}">
                    </div>

                    {{-- Certifications --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="certifications" class="block text-lg font-semibold mb-2">Certifications</label>
                        <input type="text" id="certifications" name="certifications" class="form-control rounded-md"
                            placeholder="E.g., TEFL, CELTA"
                            value="{{ old('certifications', $teacher?->certifications) }}">
                    </div>

                    {{-- Experience --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="experience" class="block text-lg font-semibold mb-2">Experience</label>
                        <input type="text" id="experience" name="experience" class="form-control rounded-md"
                            placeholder="E.g., 5 years" value="{{ old('experience', $teacher?->experience) }}">
                    </div>


                    {{-- Teaching Style --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="teaching_style" class="block text-lg font-semibold mb-2">Specialities (E.g., Conversational, Grammar-Focused)</label>
                        <input type="text" id="teaching_style" name="teaching_style" class="form-control rounded-md"
                            placeholder="E.g., Conversational, Grammar-Focused"
                            value="{{ old('teaching_style', $teacher?->teaching_style) }}">
                    </div>


                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
                        <label for="intro_video" class="block text-lg font-semibold mb-2">Intro Video</label>

                        {{-- Video Preview if Exists --}}
                        @if ($teacher?->intro_video)
                            <video class="w-full rounded-md border border-gray-300 mb-3" controls>
                                <source src="{{ asset('storage/' . $teacher->intro_video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif

                        <input type="file" id="intro_video" name="intro_video" accept="video/*"
                            class="form-control rounded-md">

                        <p class="text-sm text-gray-500 mt-2">
                            Upload an intro/demo video (MP4, WebM, MOV up to 50MB)
                        </p>
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
    <script>
        document.getElementById('image-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
