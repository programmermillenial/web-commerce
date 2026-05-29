@extends('web.layouts.app')

@push('styles')
    <style>
        .tracking-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 40px 0 25px;
        }

        .tracking-step {
            text-align: center;
            min-width: 90px;
            position: relative;
            z-index: 2;
        }

        .tracking-step .circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: 3px solid #dee2e6;
            background: #fff;
            color: #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            font-weight: bold;
            transition: 0.3s;
        }

        .tracking-step .label {
            font-weight: 600;
            color: #adb5bd;
        }

        .tracking-step.active .circle {
            border-color: #198754;
            background: #198754;
            color: #fff;
        }

        .tracking-step.active .label {
            color: #198754;
        }

        .tracking-step.current .circle {
            transform: scale(1.15);
            box-shadow: 0 0 0 6px rgba(25, 135, 84, 0.15);
        }

        .tracking-line {
            flex: 1;
            height: 4px;
            background: #dee2e6;
            margin: 0 10px;
            margin-bottom: 32px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .tracking-line.active {
            background: #198754;
        }

        @media (max-width: 576px) {
            .tracking-wrapper {
                align-items: flex-start;
            }

            .tracking-step {
                min-width: 70px;
            }

            .tracking-step .circle {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }

            .tracking-step .label {
                font-size: 13px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        @if (empty($order))
            <div class="row justify-content-center">
                <div class="col-lg-5">

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">

                            <div class="text-center mb-4">
                                <div class="container section-title" data-aos="fade-up">
                                    <p><span>Tracking </span> <span class="description-title">Order</span></p>
                                    <h2>Masukkan kode order untuk melihat status pesanan</h2>
                                </div>
                            </div>

                            <form action="{{ route('tracking.process') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">
                                        Kode Order
                                    </label>

                                    <input type="text" name="order_code" class="form-control form-control-lg"
                                        placeholder="Contoh: ORD-20260525-001" value="{{ request('order_code') }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-search me-1"></i>
                                    Cari Order
                                </button>

                            </form>

                            @if (request('order_code'))
                                <div class="alert alert-danger mt-4 mb-0">
                                    Order tidak ditemukan
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="container section-title" data-aos="fade-up">
                        <p><span>Tracking </span> <span class="description-title">Order</span></p>
                        <h2>Order: <strong>{{ $order->order_code }}</strong></h2>
                    </div>

                    @php
                        $steps = [
                            'pending' => 'Pending',
                            'process' => 'Proses',
                            'done' => 'Done',
                        ];

                        $currentStatus = $order->status;

                        $stepKeys = array_keys($steps);
                        $currentIndex = array_search($currentStatus, $stepKeys);
                    @endphp

                    <div class="tracking-wrapper">
                        @foreach ($steps as $key => $label)
                            @php
                                $index = array_search($key, $stepKeys);

                                $isActive = $index <= $currentIndex;
                                $isCurrent = $key == $currentStatus;
                            @endphp

                            <div class="tracking-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                <div class="circle">
                                    @if ($isActive)
                                        <i class="bi bi-check-lg"></i>
                                    @endif
                                </div>
                                <div class="label">{{ $label }}</div>
                            </div>

                            @if (!$loop->last)
                                <div class="tracking-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                            @endif
                        @endforeach
                    </div>

                    <hr class="my-4">

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Kode Order</small>
                            <div class="fw-bold">
                                {{ $order->order_code }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Tanggal Order</small>
                            <div class="fw-bold">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Nama Pemesan</small>
                            <div class="fw-bold">
                                {{ $order->customer_name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">WhatsApp</small>
                            <div class="fw-bold">
                                {{ $order->customer_whatsapp }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Metode Pengambilan</small>
                            <div class="fw-bold text-capitalize">
                                {{ $order->delivery_method }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Status Order</small>
                            <div>
                                @if ($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">
                                        PENDING
                                    </span>
                                @elseif($order->status == 'process')
                                    <span class="badge bg-primary">
                                        DIPROSES
                                    </span>
                                @elseif($order->status == 'done')
                                    <span class="badge bg-success">
                                        SELESAI
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($order->customer_note)
                            <div class="col-12 mb-3">
                                <small class="text-muted">Catatan</small>
                                <div class="fw-bold">
                                    {{ $order->customer_note }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <h5 class="mb-3">Detail Pesanan</h5>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->order_items as $detail)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $detail->menu_name }}
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            {{ $detail->qty }}
                                        </td>

                                        <td class="text-end">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-5">

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak</span>
                                <strong>
                                    Rp {{ number_format($order->tax_amount ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Service</span>
                                <strong>
                                    Rp {{ number_format($order->service_amount ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkir</span>
                                <strong>
                                    Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Total</span>

                                <span class="fw-bold fs-3 text-primary">
                                    Rp {{ number_format($order->grand_total ?? $order->total, 0, ',', '.') }}
                                </span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
@endsection
