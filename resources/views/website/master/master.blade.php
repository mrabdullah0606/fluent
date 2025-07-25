<!DOCTYPE html>
<html lang="en">

<head>
    @include('website.includes.head')
</head>

<body>
    @include('website.includes.navbar')
    @yield('content')
    @include('website.includes.footer')
    @push('scripts')
        <script>
            function scrollTestimonials(direction) {
                const container = document.getElementById('testimonialScroll');
                const scrollAmount = 300;
                container.scrollBy({
                    left: direction === 'left' ? -scrollAmount : scrollAmount,
                    behavior: 'smooth'
                });
            }
        </script>
    @endpush
    @include('website.includes.scripts')
</body>

</html>
