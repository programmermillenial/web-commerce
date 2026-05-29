@extends('admin.layouts.app')

@section('title', 'Edit Menu')

@section('content')
    <div class="container-fluid content-inner pb-0">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Menu</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('menu.update', $menu->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">- Pilih Category -</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Menu</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $menu->name) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control"
                                value="{{ old('slug', $menu->slug) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control"
                                value="{{ old('stock', $menu->stock) }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control">{{ old('description', $menu->description) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Featured</label>
                            <select name="is_featured" class="form-select">
                                <option value="1" {{ old('is_featured', $menu->is_featured) == 1 ? 'selected' : '' }}>
                                    Yes
                                </option>
                                <option value="0" {{ old('is_featured', $menu->is_featured) == 0 ? 'selected' : '' }}>
                                    No
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $menu->is_active) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('is_active', $menu->is_active) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                    </div>

                    <hr>

                    <h5>Image Lama</h5>

                    <div class="row g-2 mb-3">
                        @forelse($menu->images as $image)
                            <div class="col-md-2 col-4">
                                <div class="border rounded p-1 position-relative">
                                    @if ($image->is_primary == 1)
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-1">
                                            Primary
                                        </span>
                                    @endif

                                    <img src="{{ url($image->image_path) }}" class="rounded"
                                        style="width:100%;height:90px;object-fit:cover;">
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Belum ada image.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Image Baru</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*"
                            onchange="previewImages(this)">

                        <small class="text-muted">
                            Jika upload gambar baru, gambar lama akan diganti.
                        </small>
                    </div>

                    <div id="image-preview" class="row g-2 mt-3"></div>

                    <hr>

                    @php
                        $price = $menu->prices->first();
                    @endphp

                    <h5>Harga</h5>

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Normal Price</label>
                            <input type="text" name="normal_price" class="form-control price-format"
                                value="{{ old('normal_price', $price ? number_format($price->normal_price, 0, ',', '.') : '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Promo Price</label>
                            <input type="text" name="promo_price" class="form-control price-format"
                                value="{{ old('promo_price', $price && $price->promo_price ? number_format($price->promo_price, 0, ',', '.') : '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Promo Start</label>
                            <input type="datetime-local" name="promo_start" class="form-control"
                                value="{{ old('promo_start', $price && $price->promo_start ? date('Y-m-d\TH:i', strtotime($price->promo_start)) : '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Promo End</label>
                            <input type="datetime-local" name="promo_end" class="form-control"
                                value="{{ old('promo_end', $price && $price->promo_end ? date('Y-m-d\TH:i', strtotime($price->promo_end)) : '') }}">
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>

                        <a href="{{ route('menu.index') }}" class="btn btn-light">
                            Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/custom/menu/form.js') }}"></script>
@endpush
