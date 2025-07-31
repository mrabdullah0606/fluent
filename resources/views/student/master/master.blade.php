<!DOCTYPE html>
<html lang="en">

<head>
    @include('student.includes.head')
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
    {{-- @push('teacherStyles')
        <style>
            /* .brand-icon {
              background: linear-gradient(to bottom right, #ff6600, #ffcc00);
              border-radius: 10px;
              padding: 8px 10px;
            } */

            .nav-link.active {
                background-color: #fff3cd !important;
                border-radius: 8px;
            }

            .nav-link:hover {
                background-color: #f5f5f5 !important;
                border-radius: 8px;
            }

            .btn-switch {
                border: 1px solid #fdbd00;
                color: #fdbd00;
                font-weight: 500;
            }

            .btn-switch:hover {
                background-color: #FFBF00;
            }

            .nav-text-orange {
                color: #ffae00;
                font-weight: 600;
            }

            .navbar-bottom-border {
                border-bottom: 1px solid #fdbd00;
            }

            .navbar-brand.nav-text-orange:hover {
                color: #ffae00;
                /* same as default */
                text-decoration: none;
                cursor: default;
            }

            .card-border {
                border: 1px solid #fdbd00;
                border-radius: 8px;
            }

            .icon-end {
                position: absolute;
                right: 15px;
                top: 15px;
                font-size: 1.2rem;
                color: gray;
            }

            .alert-custom {
                background-color: #fffde7;
                border-left: 4px solid #fdbd00;
            }

            .lesson-card {
                border: 1px solid #fdbd00;
                border-radius: 8px;
                background-color: white;
                transition: box-shadow 0.2s;
            }

            .lesson-card:hover {
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .avatar-circle {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background-color: #f1f1f1;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: #555;
            }

            .time-text {
                color: #fcb900;
                font-size: 0.9rem;
            }

            .btn-yellow {
                background-color: rgb(232, 48, 48);
                color: #fff;
                transition: all 0.2s ease-in-out;
            }

            .btn-yellow:hover {
                background-color: #FFC519;
                transform: translateY(-2px);
                color: #fff;
            }


            .show-more-btn {
                margin-top: 1.5rem;
            }

            .btn-outline-secondary:hover {
                background-color: #FFF2CC !important;
                color: #000 !important;
                border-color: #ced4da !important;
            }
        </style>
    @endpush --}}
    <!-- Dashboard Layout Wrapper -->
    @include('student.includes.navbar')
    @yield('content')
    @include('student.includes.footer')
    @include('student.includes.scripts')
</body>

</html>
