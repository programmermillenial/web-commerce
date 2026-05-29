@extends('admin.layouts.app')

@section('title', 'Customers Data')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Data Customer</h4>
        </div>

        <div class="card-body">
            <table id="table-customer" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th>Email</th>
                        <th>Total Order</th>
                        <th>Total Spent</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-customer').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'total_orders',
                        name: 'total_orders',
                        className: 'text-center'
                    },
                    {
                        data: 'total_spent',
                        name: 'total_spent'
                    },
                    {
                        data: 'encrypted_url',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <div class="text-center">
                                <a href="${data}" class="btn btn-sm btn-primary">
                                    <i class="ri-eye-line me-1"></i> Detail
                                </a>
                            </div>
                        `;
                        }
                    }
                ]
            });
        });
    </script>
@endpush
