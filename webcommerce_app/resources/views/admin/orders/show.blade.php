@extends('admin.layouts.app')

@section('title', 'Detail Order')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h4>Detail Order #{{ $order->order_code }}</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $order->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Whatsapp</th>
                            <td>{{ $order->customer_whatsapp }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $order->customer_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $order->customer_address }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $order->customer_note ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Method</th>
                            <td>{{ $order->delivery_method }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-warning">{{ $order->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Transaction Status</th>
                            <td>
                                <span class="badge bg-success">{{ $order->transaction_status }}</span>
                            </td>
                        </tr>
                    </table>

                    @if ($order->payment_proof)
                        <h5>Bukti Transfer</h5>
                        <img src="{{ asset($order->payment_proof) }}" class="img-fluid rounded border"
                            style="max-width: 300px;">
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Item Order</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->order_items as $item)
                                <tr>
                                    <td>{{ $item->menu_name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table table-borderless">
                        <tr>
                            <th>Subtotal</th>
                            <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Pajak</th>
                            <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td class="text-end">Rp {{ number_format($order->service_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Ongkir</th>
                            <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="fs-5">Total</th>
                            <td class="text-end fs-5 fw-bold">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Aksi Order</h4>
                </div>

                <div class="card-body">

                    {{-- APPROVE PAYMENT --}}
                    @if ($order->transaction_status == 'waiting')
                        <form action="{{ route('orders.approve-payment', $order->id) }}" method="POST"
                            id="form-approve-payment">
                            @csrf

                            <button type="button" id="btn-approve-payment" class="btn btn-success w-100 mb-3">
                                <i class="ri-check-line me-1"></i> Approve Payment
                            </button>
                        </form>
                    @endif

                    {{-- UPDATE STATUS --}}
                    @if ($order->transaction_status == 'paid' && $order->status != 'done' && $order->status != 'cancel')
                        <label class="form-label">Ubah Status Order</label>

                        <select id="status" class="form-select mb-3">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="process" {{ $order->status == 'process' ? 'selected' : '' }}>Process</option>
                            <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                            <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        </select>

                        <button id="btn-update-status" class="btn btn-primary w-100">
                            Simpan Status
                        </button>
                    @endif

                    {{-- CANCEL ORDER --}}
                    @if ($order->status != 'done' && $order->status != 'cancel')
                        <button id="btn-cancel-order" class="btn btn-danger w-100 mt-2">
                            <i class="ri-close-circle-line me-1"></i> Cancel Order
                        </button>
                    @endif

                    <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100 mt-2">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#btn-update-status').on('click', function() {
            let status = $('#status').val();

            Swal.fire({
                title: 'Ubah status order?',
                text: 'Status akan diubah menjadi ' + status,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(status);
                }
            });
        });

        $('#btn-cancel-order').on('click', function() {
            Swal.fire({
                title: 'Cancel order ini?',
                text: 'Order yang dicancel tidak bisa diproses lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, cancel',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus('cancel');
                }
            });
        });

        $('#btn-approve-payment').on('click', function() {
            Swal.fire({
                title: 'Approve pembayaran?',
                text: 'Transaction status akan berubah dari waiting menjadi paid.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Ya, approve',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-approve-payment').submit();
                }
            });
        });

        function updateOrderStatus(status) {
            $.ajax({
                url: "{{ route('orders.update-status', Crypt::encryptString($order->order_code)) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    status: status
                },
                beforeSend: function() {
                    $('#btn-update-status, #btn-cancel-order').prop('disabled', true);
                    $('#btn-update-status').text('Menyimpan...');
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ?? 'Status gagal diubah',
                        'error'
                    );
                },
                complete: function() {
                    $('#btn-update-status, #btn-cancel-order').prop('disabled', false);
                    $('#btn-update-status').text('Simpan Status');
                }
            });
        }
    </script>
@endpush
