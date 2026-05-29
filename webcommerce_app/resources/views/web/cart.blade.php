@extends('web.layouts.app')

@section('content')
    <!-- Stats Section -->
    <section id="stats" class="stats section dark-background">
        <img src="{{ asset('assets/web/img/stats-bg.jpg') }}" alt="" data-aos="fade-in">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4"></div>
        </div>
    </section>

    <section class="section py-5">
        <div class="container">
            <div class="container section-title" data-aos="fade-up">
                <p><span>Keranjang</span> <span class="description-title">Belanja</span></p>
                <h2>
                    Daftar menu yang sudah kamu pilih
                </h2>
            </div>

            @if (count($cart) > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                @php
                                    $grandTotal = 0;
                                @endphp

                                @foreach ($cart as $id => $item)
                                    @php
                                        $subtotal = $item['price'] * $item['qty'];
                                        $grandTotal += $subtotal;
                                    @endphp

                                    <div class="cart-item py-3 border-bottom" id="cart-item-{{ $id }}"
                                        data-price="{{ $item['price'] }}">
                                        <div class="d-flex align-items-center">

                                            <!-- IMAGE -->
                                            <div class="me-3">
                                                <img src="{{ !empty($item['photo']) ? asset($item['photo']) : asset('images/no-image.png') }}"
                                                    class="rounded-3" style="width: 90px; height: 90px; object-fit: cover;">
                                            </div>

                                            <!-- INFO -->
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">
                                                    {{ $item['name'] }}
                                                </h5>

                                                <div class="text-muted small mb-2">
                                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                </div>

                                                <!-- QTY -->
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-secondary btn-minus"
                                                        data-id="{{ $id }}">
                                                        -
                                                    </button>

                                                    <input type="number" class="form-control text-center mx-2 qty-input"
                                                        value="{{ $item['qty'] }}" data-id="{{ $id }}"
                                                        style="width: 70px;">

                                                    <button class="btn btn-sm btn-outline-secondary btn-plus"
                                                        data-id="{{ $id }}">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- PRICE -->
                                            <div class="text-end">
                                                <div class="fw-bold mb-2 item-subtotal" id="subtotal-{{ $id }}">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </div>

                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $id }}">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- SUMMARY -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">
                                    Ringkasan Belanja
                                </h5>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Total</span>
                                    <strong id="cart-total">
                                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                    </strong>
                                </div>

                                <hr>

                                <button type="button" id="btn-checkout" class="btn btn-danger w-100 rounded-pill py-2">
                                    Checkout
                                </button>

                                <form id="form-checkout" action="{{ route('checkout.index') }}" method="GET"></form>

                                <a href="{{ url('/') }}"
                                    class="btn btn-outline-secondary w-100 rounded-pill py-2 mt-2">
                                    Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <h4 class="fw-bold">
                            Keranjang masih kosong
                        </h4>

                        <p class="text-muted">
                            Yuk pilih menu favorit kamu dulu
                        </p>

                        <a href="{{ url('/#menu') }}" class="btn btn-danger rounded-pill px-4">
                            Belanja Sekarang
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        function updateCart(id, qty) {
            let item = $('#cart-item-' + id);

            item.css({
                opacity: 0.5,
                pointerEvents: 'none'
            });

            $.ajax({
                url: "{{ route('cart.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    menu_id: id,
                    qty: qty
                },
                success: function(res) {
                    $('#cart-count').text(res.cart_count);
                    $('#cart-total').text(formatRupiah(res.cart_total));

                    let price = parseInt(item.data('price'));
                    let subtotal = price * qty;

                    $('#subtotal-' + id).text(formatRupiah(subtotal));

                    item.css({
                        opacity: 1,
                        pointerEvents: 'auto'
                    });
                },
                error: function(err) {
                    item.css({
                        opacity: 1,
                        pointerEvents: 'auto'
                    });
                    console.log(err);
                }
            });
        }

        $('.btn-plus').on('click', function() {
            let id = $(this).data('id');
            let input = $('.qty-input[data-id="' + id + '"]');
            let qty = parseInt(input.val()) + 1;
            input.val(qty);
            updateCart(id, qty);

        });

        $('.btn-minus').on('click', function() {
            let id = $(this).data('id');
            let input = $('.qty-input[data-id="' + id + '"]');
            let qty = parseInt(input.val()) - 1;

            if (qty < 1) {
                qty = 1;
            }

            input.val(qty);
            updateCart(id, qty);
        });

        $('.qty-input').on('change', function() {
            let id = $(this).data('id');
            let qty = parseInt($(this).val());

            if (qty < 1 || isNaN(qty)) {
                qty = 1;
            }

            $(this).val(qty);
            updateCart(id, qty);

        });

        $('.btn-delete').on('click', function() {
            let id = $(this).data('id');
            let btn = $(this);
            btn.prop('disabled', true);
            btn.html(`<span class="spinner-border spinner-border-sm"></span>`);

            $.ajax({
                url: "{{ route('cart.remove') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    menu_id: id
                },
                success: function(res) {
                    $('#cart-item-' + id).fadeOut(200, function() {
                        $(this).remove();
                    });
                    $('#cart-count').text(res.cart_count);
                    $('#cart-total').text(formatRupiah(res.cart_total));

                    if (res.cart_count <= 0) {
                        location.reload();
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false);
                    btn.html('Hapus');
                    console.log(err);
                }
            });

        });

        $('#btn-checkout').on('click', function() {
            Swal.fire({
                title: 'Loading...',
                text: 'Sedang menuju checkout',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $('#form-checkout').submit();
        });
    </script>
@endpush
