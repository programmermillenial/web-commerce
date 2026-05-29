@extends('admin.layouts.app')

@section('title', 'Create Menu')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Menu</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- CATEGORY --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>

                        <select name="category_id" class="form-select">
                            <option value="">- Pilih -</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Menu</label>

                        <input type="text" name="name" class="form-control">
                    </div>

                    {{-- SLUG --}}
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label">Slug</label>

                        <input type="text" name="slug" class="form-control">
                    </div> --}}

                    {{-- STOCK --}}
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label">Stock</label>

                        <input type="number" name="stock" class="form-control">
                    </div> --}}

                    {{-- DESCRIPTION --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>

                        <textarea name="description" rows="4" class="form-control"></textarea>
                    </div>

                    {{-- FEATURED --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Featured</label>

                        <select name="is_featured" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>

                        <select name="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <hr>

                <h5>Thumbnail / Gallery</h5>

                <div class="mb-3">
                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*"
                        onchange="previewImages(this)">

                    <div id="image-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

                <hr>

                <h5>Harga</h5>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Normal Price</label>

                        <input type="text" name="normal_price" id="normal_price" class="form-control price-format"
                            autocomplete="off">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Promo Price</label>

                        <input type="text" name="promo_price" id="promo_price" class="form-control price-format"
                            autocomplete="off">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Promo Start</label>

                        <input type="datetime-local" name="promo_start" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Promo End</label>

                        <input type="datetime-local" name="promo_end" class="form-control">
                    </div>

                </div>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/custom/menu/form.js') }}"></script>
@endpush
