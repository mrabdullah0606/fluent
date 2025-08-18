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

    <!-- Floating Chat Button -->
    {{-- <button class="btn btn-warning rounded-circle shadow position-fixed bottom-0 end-0 m-4"
        style="width:60px; height:60px;">
        <i class="bi bi-chat-dots fs-4 text-dark"></i>
    </button> --}}


    <!--<div id='tawk_684fef7c6b65fa190ea70353'></div>-->
    <!--Start of Tawk.to Script-->
    <!--<script type="text/javascript">-->
    <!--    var Tawk_API = Tawk_API || {},-->
    <!--        Tawk_LoadStart = new Date();-->
    <!--    (function() {-->
    <!--        var s1 = document.createElement("script"),-->
    <!--            s0 = document.getElementsByTagName("script")[0];-->
    <!--        s1.async = true;-->
    <!--        s1.src = 'https://embed.tawk.to/684fef7c6b65fa190ea70353/1j2c9ckp2';-->
    <!--        s1.charset = 'UTF-8';-->
    <!--        s1.setAttribute('crossorigin', '*');-->
    <!--        s0.parentNode.insertBefore(s1, s0);-->
    <!--    })();-->
    <!--</script>-->
    <!--End of Tawk.to Script-->
    <script>
  (function(d,t) {
    var BASE_URL="https://app.chatwoot.com";
    var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=BASE_URL+"/packs/js/sdk.js";
    g.async = true;
    s.parentNode.insertBefore(g,s);
    g.onload=function(){
      window.chatwootSDK.run({
        websiteToken: 'ArXXCvG8rCjsJ2LJvcAqAqAf',
        baseUrl: BASE_URL
      })
    }
  })(document,"script");
</script>

</footer>
