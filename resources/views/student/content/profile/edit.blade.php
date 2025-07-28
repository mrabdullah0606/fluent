@extends('student.master.master')
@section('title', 'Profile Management - FluentAll')
@section('content')
    <style>

    </style>
    {{-- <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="container my-5">
            <div class="btn mb-4">
                <a href="{{ route('teacher.public.profile') }}" class="ms-auto text-decoration-underline fw-semibold">Back</a>
            </div>

            <div class="card shadow-sm rounded-4 p-4 border-0">
                <h4 class="mb-4 text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Your Teaching Profile</h4>
                <form action="{{ route('teacher.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Headline</label>
                        <input type="text" class="form-control rounded-pill" name="headline" value="">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea class="form-control rounded-4" name="bio" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Languages You Teach</label>
                        <select name="teaches_languages[]" class="form-select" multiple>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}"
                                    {{ in_array($language->id, old('teaches_languages', [])) ? 'selected' : '' }}>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold CTRL (or CMD on Mac) to select multiple.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Languages You Speak</label>
                        <div class="row">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Country</label>
                            <input type="text" class="form-control rounded-pill" name="country" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Experience (years)</label>
                            <input type="number" class="form-control rounded-pill" name="experience_years" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Rate per Hour ($)</label>
                            <input type="number" step="0.01" class="form-control rounded-pill" name="rate_per_hour"
                                value="">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning rounded-pill px-4 py-2">
                            <i class="bi bi-save me-1"></i> Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto max-w-4xl">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8">Edit Your Student Profile</h1>
                </div>
                <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200 mb-8"
                        style="opacity: 1; transform: none;">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="flex-shrink-0 relative">
                                <span
                                    class="relative flex shrink-0 overflow-hidden rounded-full w-28 h-28 border-4 border-primary"><span
                                        class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ substr($student->name, 0, 2) }}</span></span>
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
                                    id="name" name="name" value="{{ old('name', $student->name) }}" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="aboutMe"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>About Me</label>
                            <textarea
                                class="flex min-h-[80px] w-full rounded-md border border-input px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50"
                                id="aboutMe" name="aboutMe" placeholder="Tell students a little about yourself..." rows="4">Hello! I'm Sarah, a TEFL-certified English tutor with over 5 years of experience helping students worldwide achieve their language goals.</textarea>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="teachingStyle"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>My Teaching Style</label>
                            <textarea
                                class="flex min-h-[80px] w-full rounded-md border border-input px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50"
                                id="teachingStyle" name="teachingStyle" placeholder="Describe your teaching methods and what students can expect..."
                                rows="4">My lessons are dynamic, interactive, and tailored to your specific needs. I believe in creating a supportive and fun learning environment.</textarea>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="experience"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>My Experience</label>
                            <textarea
                                class="flex min-h-[80px] w-full rounded-md border border-input px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50"
                                id="experience" name="experience" placeholder="Detail your years of experience, types of students, etc."
                                rows="3">5+ years of online and in-person teaching experience. Specialized in Business English and IELTS preparation.</textarea>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="certifications"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="mr-2 h-5 w-5 text-primary">
                                    <circle cx="12" cy="8" r="6"></circle>
                                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                </svg>My Certifications</label>
                            <textarea
                                class="flex min-h-[80px] w-full rounded-md border border-input px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50"
                                id="certifications" name="certifications"
                                placeholder="List any relevant certifications like TEFL, CELTA, or degrees." rows="2">TEFL Certified, Cambridge CELTA, MA in Applied Linguistics</textarea>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="hobbies"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <path
                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                    </path>
                                </svg>My Hobbies</label>
                            <textarea
                                class="flex min-h-[80px] w-full rounded-md border border-input px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50"
                                id="hobbies" name="hobbies" placeholder="Share some of your interests to connect with students!"
                                rows="2">In my free time, I enjoy hiking, reading, and exploring new cultures through travel and food.</textarea>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                            style="opacity: 1; transform: none;"><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground flex items-center mb-3"
                                for="cert-upload"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5 text-primary">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" x2="12" y1="3" y2="15"></line>
                                </svg>Upload Certification Documents</label>
                            <div
                                class="mt-1 flex items-center space-x-3 p-3 border border-dashed border-input rounded-md bg-gray-50 hover:border-primary transition">
                                <input type="file"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                    id="cert-upload" name="cert-upload" multiple="" accept=".pdf,.jpg,.png">
                            </div>
                            <p class="text-xs text-muted-foreground mt-1">Upload scanned copies of your certificates. (PDF,
                                JPG, PNG)</p>
                        </div>
                    </div>
                    <div class="mt-8 text-right" style="opacity: 1;"><button
                            class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 btn-red text-lg px-8 py-3"
                            type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg> Save Changes</button></div>
                </form>
            </div>
        </div>
    </main>
@endsection
