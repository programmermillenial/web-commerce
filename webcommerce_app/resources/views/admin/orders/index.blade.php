@extends('admin.layouts.app')

@section('title', 'Orders Data')

@push('styles')
    <style>
        .order-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 1px solid #eef0f5;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
            margin-bottom: 28px;
        }

        .order-tabs .nav-link {
            border: 0;
            border-radius: 0;
            padding: 24px 20px;
            color: #344054;
            font-weight: 600;
            background: #fff;
            position: relative;
        }

        .order-tabs .nav-link.active {
            color: #315bef;
            background: #fff;
        }

        .order-tabs .nav-link.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 4px;
            background: #315bef;
        }

        .order-tab-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            font-size: 24px;
        }

        .tab-pending .order-tab-icon {
            background: #eef3ff;
            color: #315bef;
        }

        .tab-process .order-tab-icon {
            background: #fff4e5;
            color: #f79009;
        }

        .tab-done .order-tab-icon {
            background: #e8f8ef;
            color: #12b76a;
        }

        .order-count {
            margin-left: 12px;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .tab-pending .order-count {
            background: #315bef;
            color: #fff;
        }

        .tab-process .order-count {
            background: #fff0d5;
            color: #f79009;
        }

        .tab-done .order-count {
            background: #d7f5e4;
            color: #12b76a;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Data Order</h4>
        </div>

        <div class="card-body">
            <ul class="nav order-tabs" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active tab-pending" data-bs-toggle="tab" data-bs-target="#pending">
                        <span class="order-tab-icon">
                            <i class="ri-time-line"></i>
                        </span>
                        Order Pending
                        <span class="order-count" id="count-pending">0</span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link tab-process" data-bs-toggle="tab" data-bs-target="#process">
                        <span class="order-tab-icon">
                            <i class="ri-loader-4-line"></i>
                        </span>
                        Order Process
                        <span class="order-count" id="count-process">0</span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link tab-done" data-bs-toggle="tab" data-bs-target="#done">
                        <span class="order-tab-icon">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                        Order Done
                        <span class="order-count" id="count-done">0</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending">
                    <table id="table-pending" class="table table-bordered table-striped w-100">
                        @include('admin.orders.partials.table-header')
                    </table>
                </div>

                <div class="tab-pane fade" id="process">
                    <table id="table-process" class="table table-bordered table-striped w-100">
                        @include('admin.orders.partials.table-header')
                    </table>
                </div>

                <div class="tab-pane fade" id="done">
                    <table id="table-done" class="table table-bordered table-striped w-100">
                        @include('admin.orders.partials.table-header')
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            initOrderTable('#table-pending', 'pending');
            initOrderTable('#table-process', 'process');
            initOrderTable('#table-done', 'done');

            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });
        });

        function initOrderTable(tableId, status) {
            $(tableId).DataTable({
                ajax: {
                    url: "{{ route('orders.list') }}",
                    data: {
                        status: status
                    }
                },
                processing: true,
                drawCallback: function() {
                    let total = this.api().page.info().recordsTotal;

                    if (status === 'pending') {
                        $('#count-pending').text(total);
                    }

                    if (status === 'process') {
                        $('#count-process').text(total);
                    }

                    if (status === 'done') {
                        $('#count-done').text(total);
                    }
                },
                columns: getOrderColumns()
            });
        }

        function getOrderColumns() {
            return [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'order_code'
                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'customer_whatsapp'
                },
                {
                    data: 'delivery_method'
                },
                {
                    data: 'total',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        let badge = 'bg-warning';

                        if (data === 'process') badge = 'bg-primary';
                        if (data === 'done') badge = 'bg-success';

                        return `<span class="badge ${badge}">${data}</span>`;
                    }
                },
                {
                    data: 'transaction_status',
                    render: function(data) {
                        let badge = 'bg-danger';

                        if (data === 'waiting') badge = 'bg-warning';
                        if (data === 'paid') badge = 'bg-success';

                        return `<span class="badge ${badge}">${data}</span>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleString('id-ID');
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let detailUrl = "{{ route('orders.show', ':code') }}";
                        detailUrl = detailUrl.replace(':code', encodeURIComponent(row.encrypted_code));

                        let approveUrl = "{{ route('orders.approve-payment', ':id') }}";
                        approveUrl = approveUrl.replace(':id', row.id);

                        let approveButton = '';

                        if (
                            row.status === 'pending' &&
                            row.transaction_status === 'waiting'
                        ) {
                            approveButton = `
                                            <form action="${approveUrl}" method="POST" class="d-inline approve-payment-form">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="ri-check-line me-1"></i> Approve
                                                </button>
                                            </form>
                                        `;
                        }

                        return `
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="${detailUrl}" class="btn btn-sm btn-primary">
                                        <i class="ri-eye-line me-1"></i> Detail
                                    </a>

                                    ${approveButton}
                                </div>
                            `;
                    }
                }
            ];
        }

        function formatRupiah(value) {
            value = parseFloat(value || 0);

            return value.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
        }

        $(document).on('submit', '.approve-payment-form', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Approve Payment?',
                text: 'Pembayaran akan dikonfirmasi dan order diproses.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#12b76a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Processing...',
                        text: 'Sedang approve pembayaran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    form.submit();
                }
            });
        });
    </script>
@endpush
