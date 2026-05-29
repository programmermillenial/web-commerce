@extends('web.layouts.app')

@push('styles')
    <style>
        .menu-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            text-align: center;
            transition: 0.3s;
            position: relative;
            border: 2px solid #f1f1f1;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .menu-image-wrapper {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .menu-img {
            width: 160px !important;
            height: 160px !important;
            object-fit: contain;
        }

        .menu-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            min-height: 50px;
        }

        .menu-description {
            font-size: 14px;
            color: #6c757d;
            min-height: 45px;
            margin-bottom: 15px;
        }

        .menu-price {
            font-size: 22px;
            font-weight: 700;
            color: #ce1212;
            margin-bottom: 15px;
        }

        .menu-price .normal-price {
            color: #999;
            text-decoration: line-through;
            font-size: 18px;
            margin-right: 8px;
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #dc3545;
            color: white;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 50px;
            font-weight: 600;
            z-index: 2;
        }

        .btn-cart {
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .menu-image-wrapper {
                height: 140px;
            }

            .menu-img {
                width: 120px !important;
                height: 120px !important;
            }
        }

        .contact {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .map-wrapper {
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 16px;
            background: #f5f5f5;
        }

        .map-wrapper iframe {
            display: block !important;
            width: 100% !important;
            height: 400px !important;
            border: 0 !important;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
        <div class="container">
            <div class="row gy-4 justify-content-center justify-content-lg-between">
                <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <h1 data-aos="fade-up">{!! $globalSetting->hero_title !!}</h1>
                    <p data-aos="fade-up" data-aos-delay="100">{{ $globalSetting->store_tagline }}</p>
                </div>
                <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                    <img src="{{ url($globalSetting->hero_image) }}" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- Menu Section -->
    <section id="menu" class="menu section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Our Menu</h2>
            <p><span>Check Our</span> <span class="description-title">{{ $globalSetting->store_name }} Menu</span>
            </p>
        </div><!-- End Section Title -->

        <div class="container">
            <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
                @foreach ($categories as $key => $category)
                    <li class="nav-item">
                        <a class="nav-link {{ $key == 0 ? 'active show' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#menu-{{ $category->slug }}">
                            <h4>{{ $category->name }}</h4>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
                @foreach ($categories as $key => $category)
                    <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="menu-{{ $category->slug }}">

                        <div class="tab-header text-center">
                            <p>Menu</p>
                            <h3>{{ $category->name }}</h3>
                        </div>

                        <div class="row g-4">
                            @forelse ($category->menus as $menu)
                                <div class="col-lg-4 col-md-6">

                                    <div class="menu-card">
                                        @if ($menu->is_featured == 1)
                                            <div class="featured-badge">
                                                ⭐ Featured
                                            </div>
                                        @endif

                                        <a href="{{ asset($menu->thumbnail) }}" class="glightbox">
                                            <div class="menu-image-wrapper">
                                                <img src="{{ asset($menu->thumbnail) }}" class="menu-img img-fluid"
                                                    alt="{{ $menu->name }}">
                                            </div>
                                        </a>

                                        <div class="menu-title">
                                            {{ $menu->name }}
                                        </div>

                                        <div class="menu-description">
                                            {{ $menu->description ?? '-' }}
                                        </div>

                                        @if ($menu->activePrice)
                                            @php
                                                $normalPrice = $menu->activePrice->normal_price;
                                                $promoPrice = $menu->activePrice->promo_price;
                                            @endphp

                                            <div class="menu-price">
                                                @if (!empty($promoPrice) && $promoPrice > 0)
                                                    <p class="price mb-1">
                                                        <span class="text-muted text-decoration-line-through me-2">
                                                            Rp {{ number_format($normalPrice, 0, ',', '.') }}
                                                        </span>

                                                        <span class="text-danger fw-bold">
                                                            Rp {{ number_format($promoPrice, 0, ',', '.') }}
                                                        </span>
                                                    </p>
                                                @else
                                                    <p class="price">
                                                        Rp {{ number_format($normalPrice, 0, ',', '.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-muted">Harga belum tersedia</p>
                                        @endif

                                        <button type="button" class="btn btn-danger btn-sm rounded-pill btn-add-cart"
                                            data-id="{{ $menu->id }}">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <p class="text-muted">Menu belum tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </section><!-- /Menu Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section dark-background">
        <img src="{{ asset('assets/web/img/stats-bg.jpg') }}" alt="" data-aos="fade-in">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Clients</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Projects</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Hours Of Support</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Workers</p>
                    </div>
                </div><!-- End Stats Item -->
            </div>
        </div>
    </section><!-- /Stats Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Gallery</h2>
            <p><span>Check</span> <span class="description-title">Our Gallery</span></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
                            {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 5000
                            },
                            "slidesPerView": "auto",
                            "centeredSlides": true,
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            },
                            "breakpoints": {
                                "320": {
                                "slidesPerView": 1,
                                "spaceBetween": 0
                                },
                                "768": {
                                "slidesPerView": 3,
                                "spaceBetween": 20
                                },
                                "1200": {
                                "slidesPerView": 5,
                                "spaceBetween": 20
                                }
                            }
                            }
                    </script>
                <div class="swiper-wrapper align-items-center">
                    @foreach ($images as $image)
                        <div class="swiper-slide">
                            <a class="glightbox" data-gallery="images-gallery" href="{{ url($image->image_path) }}">
                                <img src="{{ url($image->image_path) }}"class="img-fluid" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section><!-- /Gallery Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p><span>Need Help?</span> <span class="description-title">Contact Us</span></p>
        </div>

        <div class="container">

            <div class="mb-5 map-wrapper">
                {!! str_replace('loading="lazy"', 'loading="eager"', $globalSetting->maps_embed) !!}
            </div>

        </div>
    </section>
    <!-- /Contact Section -->
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.btn-add-cart', function() {
            let button = $(this);
            let menuId = $(this).data('id');

            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    menu_id: menuId,
                    qty: 1
                },
                success: function(res) {
                    flyToCart(button);

                    setTimeout(function() {
                        $('#cart-count').text(res.cart_count);
                    }, 650);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message ?? 'Gagal menambahkan cart'
                    });
                }
            });
        });

        function flyToCart(button) {
            let card = button.closest('.menu-card');
            let img = card.find('.menu-img');

            let cart = $('.cart-btn');
            let imgOffset = img.offset();
            let cartOffset = cart.offset();

            if (!img.length || !cart.length) return;

            let flyingImg = img.clone()
                .addClass('fly-cart-img')
                .css({
                    top: imgOffset.top - $(window).scrollTop(),
                    left: imgOffset.left,
                    opacity: 1
                });

            $('body').append(flyingImg);

            setTimeout(function() {
                flyingImg.css({
                    top: cartOffset.top - $(window).scrollTop(),
                    left: cartOffset.left,
                    width: 25,
                    height: 25,
                    opacity: 0.2
                });
            }, 50);

            setTimeout(function() {
                flyingImg.remove();

                cart.addClass('cart-bounce');

                setTimeout(function() {
                    cart.removeClass('cart-bounce');
                }, 400);
            }, 850);
        }

        window.addEventListener('load', function() {
            setTimeout(function() {
                AOS.refreshHard();
            }, 300);
        });
    </script>
@endpush
