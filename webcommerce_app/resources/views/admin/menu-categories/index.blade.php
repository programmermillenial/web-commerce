@extends('admin.layouts.app')

@section('title', 'Menu Categories')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">
                            Menu Categories
                        </h4>
                    </div>

                    <div>
                        <a href="{{ route('menu-categories.create') }}" class="btn btn-sm btn-primary rounded">
                            <i class="ri-add-box-fill"></i>
                            Add Category
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-category" class="table table-striped table-hover table-sm align-middle w-100">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th>Sort</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $("#table-category").DataTable({
                processing: true,
                ajax: "{{ route('menu-categories.list') }}",
                drawCallback: function() {
                    initTooltip();
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1,
                    },
                    {
                        data: "name",
                    },
                    {
                        data: "slug",
                    },
                    {
                        data: "icon",
                        className: "text-center",
                        render: function(data) {
                            if (!data) {
                                return "-";
                            }

                            return `
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="${data} fs-5 mb-1"></i>
                                        <small class="text-muted">${data}</small>
                                    </div>
                                `;
                        },
                    },
                    {
                        data: "is_active",
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
                        },
                    },
                    {
                        data: "sort_order",
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
                            let editUrl = "{{ route('menu-categories.edit', ':slug') }}".replace(
                                ':slug',
                                data.slug);
                            return `
                                    <div class="d-flex gap-1">
                                        <a href="${editUrl}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>

                                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="deleteCategory('${data.slug}')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>`;
                        },
                    },
                ],
            });

            function initTooltip() {
                $('[data-bs-toggle="tooltip"]').tooltip("dispose");
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        function deleteCategory(slug) {
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
                    let url = "{{ route('menu-categories.destroy', ':slug') }}"
                        .replace(':slug', slug);

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
                            $('#table-category')
                                .DataTable()
                                .ajax
                                .reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message ??
                                    'Gagal menghapus data'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
