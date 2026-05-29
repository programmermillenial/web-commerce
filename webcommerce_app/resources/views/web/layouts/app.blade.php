<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $globalSetting?->store_name ?? config('app.name') }}</title>
    <meta name="description" content="{{ $globalSetting->meta_description }}">
    <meta name="keywords" content="{{ $globalSetting->meta_keywords }}">

    <!-- Favicons -->
    @if ($globalSetting?->favicon)
        <link rel="icon" type="image/png" href="{{ asset($globalSetting->favicon) }}">
    @endif
    <link href="{{ asset('assets/web/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/web/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/web/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/web/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/web/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/web/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/web/css/main.css') }}" rel="stylesheet">
    <style>
        .cart-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: 0.2s;
            position: relative;
            color: #212529;
            text-decoration: none;
        }

        .cart-btn:hover {
            transform: translateY(-2px);
            color: #ce1212;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: #fff;
            font-size: 11px;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .fly-cart-img {
            position: fixed;
            width: 70px;
            height: 70px;
            object-fit: contain;
            z-index: 9999;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(.4, 0, .2, 1);
        }

        .cart-bounce {
            animation: cartBounce 0.4s ease;
        }

        @keyframes cartBounce {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.25);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    @stack('styles')

    <!-- =======================================================
  * Template Name: Yummy
  * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    @include('web.layouts.header')

    <main class="main">
        @yield('content')
    </main>

    @include('web.layouts.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/web/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Main JS File -->
    <script src="{{ asset('assets/web/js/main.js') }}"></script>

    <script>
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }
    </script>

    @stack('scripts')
</body>

</html>
