@extends('web.layouts.app')

@section('content')
    {{-- <section id="stats" class="stats section dark-background">
        <img src="{{ asset('assets/web/img/stats-bg.jpg') }}" alt="" data-aos="fade-in">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4"></div>
        </div>
    </section> --}}

    <section class="section py-5">
        <div class="container">

            <div class="container section-title" data-aos="fade-up">
                <p><span>Konfirmasi</span> <span class="description-title">Pembayaran</span></p>
                <h2>Silakan transfer sesuai total pesanan</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7">
                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (!empty($order))
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">

                                <div class="mb-3">
                                    <small class="text-muted">Kode Pesanan</small>
                                    <h4 class="fw-bold mb-0">{{ $order->order_code }}</h4>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Total Bayar</small>
                                    <h3 class="fw-bold text-danger mb-0">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </h3>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Tanggal Pesanan</small>
                                    <div class="fw-semibold">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>

                                <hr>

                                <h5 class="fw-bold mb-3">Rekening Pembayaran</h5>

                                <div class="bg-light rounded-4 p-3 mb-3">
                                    <div class="mb-2">
                                        <small class="text-muted">Bank</small>
                                        <div class="fw-bold">{{ $globalSetting->bank_name }}</div>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">Nomor Rekening</small>
                                        <div class="fw-bold fs-5">{{ $globalSetting->bank_account_number }}</div>
                                    </div>

                                    <div>
                                        <small class="text-muted">Nama Rekening</small>
                                        <div class="fw-bold">{{ $globalSetting->bank_account_name }}</div>
                                    </div>
                                </div>

                                <hr>

                                @if ($order->transaction_status != 'unpaid')
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="card-body p-4">

                                            <div class="alert alert-success rounded-4 border-0 mb-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill fs-4 me-2"></i>

                                                    <div>
                                                        <div class="fw-bold">
                                                            Pembayaran Sudah Dikonfirmasi
                                                        </div>

                                                        @php
                                                            $statusText = match ($order->status) {
                                                                'pending' => 'Menunggu konfirmasi pembayaran.',
                                                                'process' => 'Pesanan kamu sedang diproses.',
                                                                'done' => 'Pesanan kamu sudah selesai.',
                                                                default => 'Status pesanan tidak diketahui.',
                                                            };
                                                        @endphp

                                                        <small>
                                                            {{ $statusText }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($order->payment_proof)
                                                <div class="text-center mb-4">

                                                    <div class="fw-semibold mb-3">
                                                        Bukti Transfer
                                                    </div>

                                                    <img src="{{ asset($order->payment_proof) }}"
                                                        class="img-fluid rounded-4 border shadow-sm"
                                                        style="max-height: 250px; object-fit: contain;">

                                                </div>
                                            @endif

                                            <div class="d-grid">
                                                <a href="{{ route('tracking.show', [
                                                    'code' => Crypt::encryptString($order->order_code),
                                                ]) }}"
                                                    class="btn btn-success btn-lg rounded-4">

                                                    <i class="bi bi-truck me-2"></i>
                                                    Lihat Status Pesanan

                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    <form id="proof-form"
                                        action="{{ route('confirmation.upload-proof-process', [
                                            'code' => Crypt::encryptString($order->order_code),
                                        ]) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Upload Bukti Transfer</label>
                                            <input type="file" name="payment_proof"
                                                class="form-control @error('payment_proof') is-invalid @enderror"
                                                accept="image/*" required>

                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="button" id="btn-confirm-payment"
                                            class="btn btn-danger w-100 rounded-pill py-2">
                                            Konfirmasi Pembayaran
                                        </button>


                                    </form>
                                @endif
                                <a href="{{ route('confirmation.invoice', Crypt::encryptString($order->order_code)) }}"
                                    class="btn btn-dark w-100 rounded-pill py-2 mt-2">
                                    Download Invoice
                                </a>

                            </div>
                        </div>
                    @else
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">

                                <form id="form-search" action="{{ route('confirmation.search') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Masukkan Kode Pesanan</label>
                                        <input type="text" name="order_code" placeholder="Contoh: ORD-20260525-001"
                                            class="form-control @error('order_code') is-invalid @enderror" required>

                                        @error('order_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="button" id="btn-search-order"
                                        class="btn btn-danger w-100 rounded-pill py-2">
                                        Cari
                                    </button>
                                </form>

                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('#btn-confirm-payment').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi pembayaran?',
                text: 'Pastikan bukti transfer sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, konfirmasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Sedang mengupload bukti transfer',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('#proof-form').submit();
                }
            });
        });

        $('#btn-search-order').on('click', function() {
            Swal.fire({
                title: 'Loading...',
                text: 'Sedang mencari data',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $('#form-search').submit();
        });
    </script>
@endpush
