@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Order</h6>
                    <h3>{{ number_format($totalOrders) }}</h3>
                    <small>Hari ini: {{ number_format($todayOrders) }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Revenue</h6>
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <small>Hari ini: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6>Total Customer</h6>
                    <h3>{{ number_format($totalCustomers) }}</h3>
                    <small>Customer terdaftar</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Total Menu</h6>
                    <h3>{{ number_format($totalMenus) }}</h3>
                    <small>Menu aktif / tersedia</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Terbaru</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Transaksi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->customer->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($order->status == 'process')
                                                <span class="badge bg-primary">Process</span>
                                            @elseif ($order->status == 'done')
                                                <span class="badge bg-success">Done</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->transaction_status == 'unpaid')
                                                <span class="badge bg-danger">Unpaid</span>
                                            @elseif ($order->transaction_status == 'waiting')
                                                <span class="badge bg-warning text-dark">Waiting</span>
                                            @elseif ($order->transaction_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->transaction_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Order</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pending</span>
                        <strong>{{ $pendingOrders }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Process</span>
                        <strong>{{ $processOrders }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Done</span>
                        <strong>{{ $doneOrders }}</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Pembayaran</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Waiting Approval</span>
                        <strong>{{ $waitingPayments }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Paid</span>
                        <strong>{{ $paidOrders }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Penjualan Per Bulan</h5>
        </div>

        <div class="card-body">
            <div id="salesMonthlyChart"></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Menu Terlaris</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu</th>
                            <th>Total Qty</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bestMenus as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->menu->name ?? '-' }}</td>
                                <td>{{ $item->total_qty }}</td>
                                <td>Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data menu terjual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Penjualan',
                    data: @json($salesPerMonth)
                }],
                xaxis: {
                    categories: @json($monthLabels)
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '45%'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            };

            const chart = new ApexCharts(
                document.querySelector("#salesMonthlyChart"),
                options
            );

            chart.render();
        });
    </script>
@endpush
