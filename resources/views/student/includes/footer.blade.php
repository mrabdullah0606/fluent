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
