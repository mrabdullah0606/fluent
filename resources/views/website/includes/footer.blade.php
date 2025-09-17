@php
    use App\Models\Language;

    // Fetch all languages from the database
    $languages = Language::all();
@endphp
<footer class="bg-light border-top border-warning-subtle pt-5 pb-3">
    <div class="container-fluid px-4 px-md-5">
        <div class="row text-start text-muted">
            <!-- Teachers Column -->
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Teachers</h6>
                <ul class="list-unstyled">
                    @foreach ($languages as $language)
                        <li><a href="{{ route('languages.teachers', $language) }}"
                                class="text-muted text-decoration-none">{{ $language->name }} Teachers</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- About Us Column -->
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">About Us</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">Why
                            FluentAll?</a></li>
                    <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">About
                            FluentAll</a></li>
                    <li><a href="{{ route('teacher.register') }}" class="text-muted text-decoration-none">Be
                            a Teacher <i class="bi bi-box-arrow-up-right ms-1"></i></a></li>
                    <li><a href="{{ route('careers') }}" class="text-muted text-decoration-none">We are
                            hiring!
                            <i class="bi bi-box-arrow-up-right ms-1"></i></a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Contact</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope text-warning me-2"></i>
                        <a href="mailto:support@fluentall.com"
                            class="text-muted text-decoration-none">support@fluentall.com</a>
                    </li>
                    <li>
                        <i class="bi bi-chat-left-text text-warning me-2"></i>
                        <a href="#" class="text-muted text-decoration-none">Contact Form</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="border-top border-warning-subtle mt-4 pt-3 text-center text-muted small">
            <p class="mb-1">© 2025 FluentAll. All rights reserved.</p>
            <p class="mb-0">Be fluent in all Languages you want.</p>
        </div>
    </div>

    {{-- Floating Chatbot Button --}}
    <div id="chatbot-container">
        @auth
            @if (auth()->user()->role === 'student')
                <a href="{{ route('student.chat.support') }}" id="chatbot-button">💬</a>
            @elseif(auth()->user()->role === 'teacher')
                <a href="{{ route('teacher.chat.support') }}" id="chatbot-button">💬</a>
            @endif
        @else
            <div id="chatbot-button" onclick="showLoginMessage()">💬</div>
        @endauth
    </div>

    <script>
        function showLoginMessage() {
            alert("Please log in or create an account to use the support chat.");
        }
    </script>

    <style>
        /* Floating Chatbot Button */
        #chatbot-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #fdbd00;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: transform 0.2s;
            text-decoration: none;
        }

        #chatbot-button:hover {
            transform: scale(1.1);
        }
    </style>

</footer>
