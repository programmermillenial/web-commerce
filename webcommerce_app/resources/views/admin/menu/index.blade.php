@extends('admin.layouts.app')

@section('title', 'Menu')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">List Menu</h4>
            </div>

            <a href="{{ route('menu.create') }}" class="btn btn-sm btn-primary rounded">
                <i class="ri-add-box-fill"></i>
                Add Menu
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="table-menu" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="90">Thumbnail</th>
                            <th>Menu</th>
                            <th>Category</th>
                            <th width="150">Price</th>
                            <th width="120">Promo</th>
                            <th width="80">Stock</th>
                            <th width="100">Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#table-menu').DataTable({
                processing: true,
                ajax: "{{ route('menu.list') }}",
                responsive: true,
                autoWidth: false,
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
                            if (!data) {
                                return `
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                            style="width:60px;height:60px;">
                                            -
                                        </div>
                                    `;
                            }

                            return `
                                        <img src="${baseUrl}/${data}"
                                            class="rounded border"
                                            style="width:60px;height:60px;object-fit:cover;">
                                    `;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            let featured = data.is_featured == 1 ?
                                `<span class="badge bg-warning ms-1">Featured</span>` : '';

                            return `
                                    <div>
                                        <div class="fw-bold">
                                            ${data.name}
                                            ${featured}
                                        </div>

                                        <small class="text-muted">
                                            ${data.slug ?? '-'}
                                        </small>
                                    </div>
                                    `;
                        }
                    },
                    {
                        data: 'category.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'prices',
                        className: 'text-end',
                        render: function(data) {
                            if (!data || data.length == 0) {
                                return '-';
                            }
                            let normal = parseInt(data[0].normal_price ?? 0);
                            return `
                                    <span class="fw-bold text-dark">
                                        Rp ${normal.toLocaleString('id-ID')}
                                    </span>
                                `;
                        }
                    },
                    {
                        data: 'prices',
                        className: 'text-end',
                        render: function(data) {
                            if (!data || data.length == 0) {
                                return '-';
                            }

                            let promo = data[0].promo_price;
                            if (!promo || promo == 0) {
                                return `
                                            <span class="text-muted">
                                                No Promo
                                            </span>
                                        `;
                            }

                            return `
                                    <span class="text-danger fw-bold">
                                        Rp ${parseInt(promo).toLocaleString('id-ID')}
                                    </span>
                                `;
                        }
                    },
                    {
                        data: 'stock',
                        className: 'text-center',
                        render: function(data) {
                            let badge = 'success';

                            if (data <= 0) {
                                badge = 'danger';
                            } else if (data <= 10) {
                                badge = 'warning';
                            }

                            return `
                                    <span class="badge bg-${badge}">
                                        ${data}
                                    </span>
                                `;
                        }
                    },

                    // STATUS
                    {
                        data: 'is_active',
                        className: 'text-center',
                        render: function(data) {
                            if (data == 1) {
                                return `
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    `;
                            }

                            return `
                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>
                                `;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
                            let editUrl = "{{ route('menu.edit', ':slug') }}".replace(':slug',
                                data.slug);

                            return `
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="${editUrl}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>

                                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="deleteMenu(${data.id})">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                `;
                        }
                    },

                ]
            });
        });

        // DELETE
        function deleteMenu(id) {
            Swal.fire({
                title: 'Hapus menu?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('menu.destroy', ':id') }}"
                        .replace(':id', id);

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {

                            Swal.fire({
                                title: 'Loading...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#table-menu')
                                .DataTable()
                                .ajax
                                .reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message ?? 'Gagal menghapus data'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
