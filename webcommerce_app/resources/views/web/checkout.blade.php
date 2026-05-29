@extends('web.layouts.app')

@section('content')
    @php
        $shippingCostSetting = $globalSetting->shipping_cost ?? 0;
        $taxPercent = $globalSetting->tax_percent ?? 0;
        $servicePercent = $globalSetting->service_percent ?? 0;

        $subtotal = $cartTotal;
        $taxAmount = ($subtotal * $taxPercent) / 100;
        $serviceAmount = ($subtotal * $servicePercent) / 100;
        $shippingCost = $shippingCostSetting;
        $grandTotal = $subtotal + $taxAmount + $serviceAmount + $shippingCost;
    @endphp

    <!-- Stats Section -->
    <section id="stats" class="stats section dark-background">
        <img src="{{ asset('assets/web/img/stats-bg.jpg') }}" alt="" data-aos="fade-in">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4"></div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-4">
                                <strong>Data belum lengkap:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h4 class="mb-4">Data Pemesan</h4>

                        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Pemesan</label>

                                <input type="text" name="customer_name"
                                    class="form-control @error('customer_name') is-invalid @enderror"
                                    value="{{ old('customer_name') }}">

                                @error('customer_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. HP / WhatsApp</label>

                                <input type="text" name="customer_whatsapp"
                                    class="form-control @error('customer_whatsapp') is-invalid @enderror"
                                    value="{{ old('customer_whatsapp') }}">

                                @error('customer_whatsapp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="customer_email" class="form-control"
                                    value="{{ old('customer_email') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Metode Pengambilan</label>
                                <select name="delivery_method" id="delivery_method" class="form-control" required>
                                    <option value="delivery" {{ old('delivery_method') == 'delivery' ? 'selected' : '' }}>
                                        Delivery
                                    </option>

                                    <option value="pickup" {{ old('delivery_method') == 'pickup' ? 'selected' : '' }}>
                                        Pickup
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3" id="address-wrapper">
                                <label class="form-label">Alamat</label>
                                <textarea name="customer_address" id="customer_address" class="form-control" rows="4">{{ old('customer_address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="customer_note" class="form-control" rows="3">{{ old('customer_note') }}</textarea>
                            </div>

                            <input type="hidden" name="voucher_code" id="voucher_code_hidden">
                            <input type="hidden" name="discount_amount" id="discount_amount_hidden" value="0">

                            <input type="hidden" name="shipping_cost" id="shipping_cost"
                                value="{{ $shippingCostSetting }}">

                            <button type="button" id="btn-order" class="btn btn-danger w-100 rounded-pill">
                                Buat Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold">Kode Voucher</label>

                        <div class="input-group">
                            <input type="text" id="voucher_code" class="form-control"
                                placeholder="Masukkan kode voucher">

                            <button type="button" id="btn-apply-voucher" class="btn btn-danger">
                                Gunakan
                            </button>
                        </div>

                        <div id="voucher-info" class="mt-2 small"></div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4">Ringkasan Order</h4>

                        @foreach ($cart as $item)
                            <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                                <div>
                                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                                    <small class="text-muted">
                                        {{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </small>
                                </div>

                                <strong>
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </strong>
                            </div>
                        @endforeach

                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Voucher</span>
                                <strong class="text-danger" id="discount-text">- Rp 0</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax {{ $taxPercent }}%</span>
                                <strong>Rp {{ number_format($taxAmount, 0, ',', '.') }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Service {{ $servicePercent }}%</span>
                                <strong>Rp {{ number_format($serviceAmount, 0, ',', '.') }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim</span>
                                <strong id="shipping-cost-text">
                                    Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <h3 class="fw-bold mb-0">
                                    TOTAL
                                </h3>

                                <h1 class="fw-bolder text-danger mb-0" id="grand-total-text">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </h1>
                            </div>
                        </div>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-3">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const deliveryMethod = document.getElementById('delivery_method');
        const shippingInput = document.getElementById('shipping_cost');
        const shippingText = document.getElementById('shipping-cost-text');
        const grandTotalText = document.getElementById('grand-total-text');
        const addressWrapper = document.getElementById('address-wrapper');
        const customerAddress = document.getElementById('customer_address');

        const subtotal = {{ $subtotal }};
        const taxAmount = {{ $taxAmount }};
        const serviceAmount = {{ $serviceAmount }};
        const shippingCostSetting = {{ $shippingCostSetting }};

        let discountAmount = 0;
        let isFreeShipping = false;

        function calculateTotal() {
            let shippingCost = deliveryMethod.value === 'pickup' ? 0 : shippingCostSetting;

            if (isFreeShipping && deliveryMethod.value === 'delivery') {
                shippingCost = 0;
            }

            let total = subtotal - discountAmount + taxAmount + serviceAmount + shippingCost;

            if (total < 0) total = 0;

            shippingInput.value = shippingCost;
            shippingText.innerText = formatRupiah(shippingCost);

            $('#discount-text').text('- ' + formatRupiah(discountAmount));
            $('#discount_amount_hidden').val(discountAmount);

            grandTotalText.innerText = formatRupiah(total);

            if (deliveryMethod.value === 'pickup') {
                addressWrapper.style.display = 'none';
                customerAddress.removeAttribute('required');
                customerAddress.value = '-';
            } else {
                addressWrapper.style.display = 'block';
                customerAddress.setAttribute('required', true);

                if (customerAddress.value === '-') {
                    customerAddress.value = '';
                }
            }
        }

        function removeVoucherSilently() {
            discountAmount = 0;
            isFreeShipping = false;

            $('#voucher_code').val('');
            $('#voucher_code_hidden').val('');
            $('#discount_amount_hidden').val(0);
            $('#voucher-info').html('');

            $.ajax({
                url: "{{ route('checkout.remove-voucher') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });

            calculateTotal();
        }

        $('#btn-apply-voucher').on('click', function() {
            let code = $('#voucher_code').val();

            if (!code) {
                Swal.fire('', 'Kode voucher wajib diisi.', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('checkout.apply-voucher') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    code: code,
                    delivery_method: deliveryMethod.value
                },
                beforeSend: function() {
                    $('#btn-apply-voucher')
                        .prop('disabled', true)
                        .text('Cek...');
                },
                success: function(res) {
                    discountAmount = parseFloat(res.voucher.discount || 0);
                    isFreeShipping = res.voucher.free_shipping ? true : false;

                    $('#voucher_code_hidden').val(res.voucher.code);
                    $('#discount_amount_hidden').val(discountAmount);

                    $('#voucher-info').html(`
                            <span class="badge bg-success">
                                Voucher ${res.voucher.code} digunakan
                            </span>
                            <button type="button" id="btn-remove-voucher"
                                class="btn btn-sm btn-link text-danger p-0 ms-2">
                                Hapus
                            </button>
                    `);
                    calculateTotal();
                    Swal.fire('', res.message, 'success');
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Voucher tidak valid.';
                    Swal.fire('', message, 'warning');
                },
                complete: function() {
                    $('#btn-apply-voucher')
                        .prop('disabled', false)
                        .text('Gunakan');
                }
            });
        });

        $('#btn-order').on('click', function() {
            Swal.fire({
                title: 'Buat pesanan?',
                text: 'Pastikan data pemesan sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, buat pesanan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Sedang membuat pesanan',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('#checkout-form').submit();
                }
            });
        });

        $(document).on('click', '#btn-remove-voucher', function() {
            removeVoucherSilently();
        });

        deliveryMethod.addEventListener('change', function() {
            if ($('#voucher_code_hidden').val()) {
                removeVoucherSilently();
                Swal.fire('', 'Voucher dihapus karena metode pengambilan berubah.', 'info');
            }

            calculateTotal();
        });

        calculateTotal();
    </script>
@endpush
