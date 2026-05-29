@extends('admin.layouts.app')

@section('title', 'Voucher Data')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Data Voucher</h4>

            <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-1"></i> Tambah Voucher
            </a>
        </div>

        <div class="card-body">
            <table id="table-voucher" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Quota</th>
                        <th>Used</th>
                        <th>Status</th>
                        <th>Expired</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#table-voucher').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vouchers.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'code'
                },
                {
                    data: 'name'
                },
                {
                    data: 'type'
                },
                {
                    data: 'value'
                },
                {
                    data: 'minimum_order'
                },
                {
                    data: 'quota'
                },
                {
                    data: 'used'
                },
                {
                    data: 'is_active'
                },
                {
                    data: 'end_date'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus voucher?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@endpush
