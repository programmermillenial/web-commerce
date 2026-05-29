@extends('admin.layouts.app')

@section('title', 'Customer Report')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0">Filter Customer Report</h4>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" id="date_from" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" id="date_to" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="button" id="btn-filter" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i> Filter
                    </button>

                    <button type="button" id="btn-reset" class="btn btn-secondary">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Customer Report</h5>

            <a href="{{ route('reports.customer.export') }}" class="btn btn-success btn-sm">
                <i class="ri-file-excel-2-line me-1"></i> Export Excel
            </a>
        </div>

        <div class="card-body">
            <table id="table-customer-report" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Customer</th>
                        <th>WhatsApp</th>
                        <th>Menu Dibeli</th>
                        <th>Total Order</th>
                        <th>Total Spend</th>
                        <th>Last Order</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr style="background: #fff3cd; font-weight: 900; color:black">
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th id="footer-total-orders">0</th>
                        <th id="footer-total-spent">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#table-customer-report').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('reports.customer.list') }}",
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'customer_name'
                    },
                    {
                        data: 'customer_whatsapp'
                    },
                    {
                        data: 'menus_bought',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_orders'
                    },
                    {
                        data: 'total_spent_format'
                    },
                    {
                        data: 'last_order_format'
                    },
                ],
                drawCallback: function(settings) {
                    let json = this.api().ajax.json();

                    $('#footer-total-orders').html(json.total_orders_all ?? 0);
                    $('#footer-total-spent').html(formatRupiah(json.total_spent_all ?? 0));
                }
            });

            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });

            $('#btn-reset').on('click', function() {
                $('#date_from').val('');
                $('#date_to').val('');
                table.ajax.reload();
            });
        });

        function formatRupiah(value) {
            return 'Rp ' + parseFloat(value || 0).toLocaleString('id-ID');
        }
    </script>
@endpush
