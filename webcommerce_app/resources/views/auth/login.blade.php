<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $globalSetting?->store_name ?? config('app.name') }}</title>


    <!-- Favicon -->
    @if ($globalSetting?->favicon)
        <link rel="icon" type="image/png" href="{{ asset($globalSetting->favicon) }}">
    @endif

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/core/libs.min.css') }}" />


    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/hope-ui.min.css?v=2.0.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.min.css?v=2.0.0') }}" />

    <!-- Dark Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/dark.min.cs') }}s" />

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/customizer.min.css') }}" />

    <!-- RTL Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.min.css') }}" />
    <style>
        .logo-main img {
            max-height: 30px;
            width: auto;
            display: block;
        }

        .auth-card {
            border-radius: 12px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
        }

        #togglePassword svg {
            opacity: 0.6;
            transition: 0.2s;
        }

        #togglePassword:hover svg {
            opacity: 1;
        }
    </style>

    <!-- reCaptcha V3 -->
    {{-- <script src="https://www.google.com/recaptcha/api.js?render=6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q"></script>
    <script type="text/javascript">
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q', {
                action: 'homepage_login'
            }).then(function(token) {
                document.getElementById("token").value = token;
            });
        });

        setInterval(function() {
            grecaptcha.ready(function() {
                grecaptcha.execute("6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q", {
                    action: "homepage_login_request_call_back"
                }).then(function(token) {
                    document.getElementById("token").value = token;
                });
            });
        }, 90 * 1000);
    </script> --}}

</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-header">
                                    @include('admin.layouts.flash_message')
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        @if ($globalSetting?->logo)
                                            <img src="{{ asset($globalSetting->logo) }}" alt="logo"
                                                style="height: 50px;" class="mb-3">
                                        @endif

                                        <h4 class="fw-bold mb-1">{{ config('app.name') }}</h4>
                                        <p class="text-muted mb-0" style="font-size: 14px;">Please Login Here</p>
                                    </div>

                                    <form autocomplete="off" class="mt-4" method="POST"
                                        action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control form-control-lg" id="username"
                                                name="username">
                                        </div>

                                        <div class="form-group mb-4 position-relative">
                                            <label for="password" class="form-label">Password</label>

                                            <input type="password" class="form-control form-control-lg pe-5"
                                                id="password" name="password">

                                            <span id="togglePassword"
                                                style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
                                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20"
                                                    height="20" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </span>
                                        </div>

                                        {{-- <input type="hidden" id="token" name="token"> --}}

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                Sign In
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg">
                        <svg width="280" height="230" viewBox="0 0 431 398" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.05">
                                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                                    transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('assets/admin/images/auth/01.png') }}"
                        class="img-fluid gradient-main animated-scaleX" alt="images">
                </div>
            </div>
        </section>
    </div>

    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/admin/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/admin/js/core/external.min.js') }}"></script>

    <!-- Widgetchart Script -->
    <script src="{{ asset('assets/admin/js/charts/widgetcharts.js') }}"></script>

    <!-- mapchart Script -->
    <script src="{{ asset('assets/admin/js/charts/vectore-chart.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts/dashboard.js') }}"></script>

    <!-- fslightbox Script -->
    <script src="{{ asset('assets/admin/js/plugins/fslightbox.js') }}"></script>

    <!-- Settings Script -->
    <script src="{{ asset('assets/admin/js/plugins/setting.js') }}"></script>

    <!-- Slider-tab Script -->
    <script src="{{ asset('assets/admin/js/plugins/slider-tabs.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('assets/admin/js/plugins/form-wizard.js') }}"></script>

    <!-- AOS Animation Plugin-->

    <!-- App Script -->
    <script src="{{ asset('assets/admin/js/hope-ui.js') }}" defer></script>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        toggle.addEventListener('click', function() {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';

            // ganti icon (mata buka / tutup)
            icon.innerHTML = isPassword ?
                `<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.94M1 1l22 22"/>` :
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        });
    </script>

</body>

</html>
