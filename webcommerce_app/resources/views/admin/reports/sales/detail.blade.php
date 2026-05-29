@extends('admin.layouts.app')

@section('title', 'Sales Report Detail')

@section('content')
    @php
        $items = $order->order_items;
    @endphp

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Sales Detail</h5>
                <small class="text-muted">{{ $order->order_code }}</small>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('reports.sales') }}" class="btn btn-light border btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>

                <a href="{{ route('reports.sales.detail.export-pdf', Crypt::encryptString($order->order_code)) }}"
                    class="btn btn-danger btn-sm">
                    <i class="ri-file-pdf-2-line me-1"></i> Download PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Order Information</h6>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="160">Order Code</td>
                            <td>: {{ $order->order_code }}</td>
                        </tr>
                        <tr>
                            <td>Order Date</td>
                            <td>: {{ optional($order->created_at)->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>: {{ ucfirst($order->status) }}</td>
                        </tr>
                        <tr>
                            <td>Payment Status</td>
                            <td>: {{ ucfirst($order->transaction_status) }}</td>
                        </tr>
                        <tr>
                            <td>Delivery Method</td>
                            <td>: {{ ucfirst($order->delivery_method) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Customer Information</h6>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="160">Name</td>
                            <td>: {{ $order->customer->name ?? ($order->customer_name ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td>WhatsApp</td>
                            <td>: {{ $order->customer_whatsapp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>: {{ $order->customer_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>: {{ $order->customer_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Note</td>
                            <td>: {{ $order->customer_note ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Menu Detail</h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Menu</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->menu->name ?? ($item->menu_name ?? '-') }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-end">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    Rp {{ number_format(($item->price ?? 0) * ($item->qty ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No item found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-5">
                    <table class="table table-sm">
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-end">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td class="text-end">Rp {{ number_format($order->tax_amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Service</td>
                            <td class="text-end">Rp {{ number_format($order->service_amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Shipping Cost</td>
                            <td class="text-end">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Voucher</td>
                            <td class="text-end text-danger">
                                - Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="fw-bold fs-5">
                            <td>Grand Total</td>
                            <td class="text-end">
                                Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
