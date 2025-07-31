<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.includes.head')
</head>

<body>
    @if (session('success') || session('danger'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
        @if (session('success'))
        <div class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
        @if (session('danger'))
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('danger') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>
    @endif

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
