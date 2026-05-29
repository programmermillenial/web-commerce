@extends('admin.layouts.app')

@section('title', 'Customer Detail')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3">Detail Customer</h5>

                    <p class="mb-1"><strong>Nama</strong></p>
                    <p>{{ $customer->name }}</p>

                    <p class="mb-1"><strong>WhatsApp</strong></p>
                    <p>{{ $customer->phone }}</p>

                    <p class="mb-1"><strong>Email</strong></p>
                    <p>{{ $customer->email ?? '-' }}</p>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Total Order</span>
                        <strong>{{ $customer->total_orders ?? 0 }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Total Spent</span>
                        <strong>Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</strong>
                    </div>

                    <a href="{{ route('customers.index') }}" class="btn btn-secondary w-100 mt-4">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Order</h5>
                </div>

                <div class="card-body">
                    <table id="table-order-customer" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Order Code</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer->orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->order_code }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($order->transaction_status) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($customer->orders->count() == 0)
                        <p class="text-muted text-center mb-0">Belum ada order.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Jumlah Menu Dibeli</h5>
                </div>

                <div class="card-body">
                    <table id="table-menu-summary" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Menu</th>
                                <th width="20%">Total Qty</th>
                                <th width="25%">Total Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menuSummary as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->menu_name }}</td>
                                    <td class="text-center">
                                        <strong>{{ $item->total_qty }}</strong>
                                    </td>
                                    <td>
                                        Rp {{ number_format($item->total_subtotal ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada menu yang dibeli.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-order-customer').DataTable();
            $('#table-menu-summary').DataTable();
        });
    </script>
@endpush
