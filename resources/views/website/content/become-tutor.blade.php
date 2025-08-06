@extends('website.master.master')
@section('title', 'Become Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 hero-pattern-custom">
            <div class="max-w-3xl mx-auto" style="opacity: 1; transform: none;">
                <div class="text-center mb-10 md:mb-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-16 w-16 mx-auto text-primary mb-4">
                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <h1 class="text-4xl md:text-5xl font-bold text-foreground">Become a <span
                            class="text-gradient-yellow-red">fluentAll</span> Tutor</h1>
                    <p class="mt-4 text-lg text-muted-foreground max-w-xl mx-auto">Share your language expertise, inspire
                        learners, and earn flexibly. Join our global community of talented tutors!</p>
                </div>
                <form class="bg-white p-8 md:p-10 rounded-xl shadow-xl border border-primary/20 space-y-6 md:space-y-8"
                    style="opacity: 1; transform: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label
                                class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                                for="fullName">Full Name</label>
                            <div class="relative mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-muted-foreground">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg><input type="text"
                                    class="flex h-10 w-full rounded-md border px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10 bg-background border-input"
                                    id="fullName" name="fullName" placeholder="e.g., Jane Doe" required=""
                                    value=""></div>
                        </div>
                        <div><label
                                class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                                for="email">Email Address</label>
                            <div class="relative mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-muted-foreground">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg><input type="email"
                                    class="flex h-10 w-full rounded-md border px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10 bg-background border-input"
                                    id="email" name="email" placeholder="you@example.com" required=""
                                    value=""></div>
                        </div>
                    </div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="phone">Phone Number (Optional)</label>
                        <div class="relative mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-muted-foreground">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg><input type="tel"
                                class="flex h-10 w-full rounded-md border px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10 bg-background border-input"
                                id="phone" name="phone" placeholder="+1 (555) 123-4567" value=""></div>
                    </div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="languagesTaught">Languages You Teach</label>
                        <div class="relative mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-muted-foreground">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg><input type="text"
                                class="flex h-10 w-full rounded-md border px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10 bg-background border-input"
                                id="languagesTaught" name="languagesTaught" placeholder="e.g., English, Spanish, French"
                                required="" value=""></div>
                        <p class="text-xs text-muted-foreground mt-1">Please list all languages you are proficient in
                            teaching.</p>
                    </div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="experience">Teaching Experience (Years)</label><input type="number"
                            class="flex h-10 w-full rounded-md border px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-background border-input mt-1"
                            id="experience" name="experience" min="0" placeholder="e.g., 5" required=""
                            value=""></div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="qualifications">Qualifications &amp; Certifications</label>
                        <textarea
                            class="flex min-h-[80px] w-full rounded-md border px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-background border-input mt-1"
                            id="qualifications" name="qualifications" rows="3"
                            placeholder="e.g., TEFL Certified, MA in Linguistics, Native Speaker..." required=""></textarea>
                    </div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="cv">Upload Your CV/Resume</label>
                        <div
                            class="mt-1 flex items-center space-x-3 p-3 border border-dashed border-input rounded-md bg-background hover:border-primary transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-6 w-6 text-muted-foreground">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" x2="12" y1="3" y2="15"></line>
                            </svg><input type="file"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                id="cv" name="cv" accept=".pdf,.doc,.docx" required=""></div>
                        <p class="text-xs text-muted-foreground mt-1">Accepted formats: PDF, DOC, DOCX. Max size: 5MB.</p>
                    </div>
                    <div><label
                            class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground font-medium"
                            for="coverLetter">Short Cover Letter / Introduction</label>
                        <textarea
                            class="flex min-h-[80px] w-full rounded-md border px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-background border-input mt-1"
                            id="coverLetter" name="coverLetter" rows="4"
                            placeholder="Tell us a bit about yourself and your teaching philosophy..." required=""></textarea>
                    </div><button
                        class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 w-full btn-red text-lg py-3"
                        type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-5 w-5">
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg> Apply Now</button>
                </form>
            </div>
        </div>
    </main>
@endsection
