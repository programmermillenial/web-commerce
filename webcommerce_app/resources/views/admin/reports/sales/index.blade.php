@extends('admin.layouts.app')

@section('title', 'Sales Report')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Orders</small>
                    <h4 class="fw-bold mb-0" id="total-orders">0</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Sales</small>
                    <h4 class="fw-bold mb-0 text-success" id="total-sales">Rp 0</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Tax</small>
                    <h4 class="fw-bold mb-0" id="total-tax">Rp 0</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Service</small>
                    <h4 class="fw-bold mb-0" id="total-service">Rp 0</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm rounded-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Filter Sales Report</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" id="start_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" id="end_date" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Order Status</label>
                    <select id="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="process">Process</option>
                        <option value="done">Done</option>
                        <option value="cancel">Cancel</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Delivery Method</label>
                    <select id="delivery_method" class="form-select">
                        <option value="">All</option>
                        <option value="delivery">Delivery</option>
                        <option value="pickup">Pickup</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="button" id="btn-filter" class="btn btn-primary w-100">
                        Filter
                    </button>

                    <button type="button" id="btn-reset" class="btn btn-secondary">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sales Report</h5>

            <button type="button" id="btn-export" class="btn btn-success btn-sm">
                <i class="ri-file-excel-2-line me-1"></i>
                Export Excel
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="table-sales-report" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Date</th>
                            <th>Order Code</th>
                            <th>Customer</th>
                            <th>Menu</th>
                            <th>Qty</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="background: #fff3cd; font-weight: 900; color:black">
                            <th colspan="5" class="text-end">TOTAL</th>
                            <th id="footer-total-qty">0</th>
                            <th id="footer-grand-total">Rp 0</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatRupiah(value) {
            value = parseFloat(value || 0);

            return 'Rp ' + value.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        let table = $('#table-sales-report').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [1, 'desc']
            ],
            ajax: {
                url: "{{ route('reports.sales.list') }}",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status').val();
                    d.delivery_method = $('#delivery_method').val();
                },
                dataSrc: function(json) {
                    $('#total-orders').text(json.summary.total_orders);
                    $('#total-sales').text(formatRupiah(json.summary.total_sales));
                    $('#total-tax').text(formatRupiah(json.summary.total_tax));
                    $('#total-service').text(formatRupiah(json.summary.total_service));
                    $('#footer-total-qty').text(json.summary.total_qty);


                    $('#footer-grand-total').text(
                        formatRupiah(json.summary.total_sales)
                    );

                    return json.data;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'order_date',
                    name: 'created_at'
                },
                {
                    data: 'order_code',
                    name: 'order_code'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'menu_detail',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'total_qty',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'grand_total',
                    name: 'grand_total'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'delivery_method',
                    name: 'delivery_method'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#btn-filter').on('click', function() {
            table.ajax.reload();
        });

        $('#btn-reset').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status').val('');
            $('#delivery_method').val('');

            table.ajax.reload();
        });

        $('#btn-export').on('click', function() {
            let params = $.param({
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                status: $('#status').val(),
                delivery_method: $('#delivery_method').val()
            });

            window.location.href = "{{ route('reports.sales.export') }}?" + params;
        });
    </script>
@endpush
