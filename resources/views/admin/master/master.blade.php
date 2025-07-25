<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.includes.head')
</head>

<body>
    @push('styles')
        <style>

        </style>
    @endpush
    <div class="container-fluid">
        <!-- Dashboard Layout Wrapper -->
        <div class="dashboard-container">
            @include('admin.includes.sidebar')
            @yield('content')
        </div>
    </div>
    @include('admin.includes.footer')
    @include('admin.includes.scripts')
</body>

</html>
