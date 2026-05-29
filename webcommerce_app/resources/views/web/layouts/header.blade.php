<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset($globalSetting->logo) }}" alt="logo">
            <h1 class="sitename">{{ $globalSetting?->store_name }}</h1>
            <span>.</span>
        </a>

        @if (request()->routeIs('landing'))
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home<br></a></li>
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        @else
            <a href="{{ url('/') }}" class="btn btn-danger btn-sm rounded-pill px-4 ms-auto me-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        @endif

        <div class="d-flex align-items-center gap-3">
            <a class="btn btn-danger btn-sm rounded-pill px-4 ms-auto"
                href="{{ route('confirmation.index') }}">Konfirmasi Pembayaran</a>
            <a class="btn btn-danger btn-sm rounded-pill px-4 ms-auto" href="{{ route('tracking.index') }}">Status
                Pesanan</a>
            <a href="{{ route('cart.index') }}" class="cart-btn">
                <i class="bi bi-cart3 fs-5"></i>

                <span class="cart-badge" id="cart-count">
                    {{ collect(session('cart', []))->sum('qty') }}
                </span>
            </a>
        </div>
    </div>
</header>
