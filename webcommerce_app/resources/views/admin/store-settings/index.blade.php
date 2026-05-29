@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('store-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pengaturan Toko</h4>
                    </div>
                    <hr>

                    <div class="card-body">

                        <h5 class="mb-3">Identitas Toko</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Toko</label>
                                <input type="text" name="store_name" class="form-control"
                                    value="{{ old('store_name', $setting->store_name) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tagline</label>
                                <input type="text" name="store_tagline" class="form-control"
                                    value="{{ old('store_tagline', $setting->store_tagline) }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="store_description" class="form-control" rows="3">{{ old('store_description', $setting->store_description) }}</textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Logo</label>
                                <input type="file" name="logo" class="form-control">
                                @if ($setting->logo)
                                    <img src="{{ asset($setting->logo) }}" class="mt-2" width="90">
                                @endif
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control">
                                @if ($setting->favicon)
                                    <img src="{{ asset($setting->favicon) }}" class="mt-2" width="40">
                                @endif
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Hero Image</label>
                                <input type="file" name="hero_image" class="form-control">
                                @if ($setting->hero_image)
                                    <img src="{{ asset($setting->hero_image) }}" class="mt-2" width="120">
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Kontak & Alamat</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $setting->phone) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Whatsapp</label>
                                <input type="text" name="whatsapp" class="form-control"
                                    value="{{ old('whatsapp', $setting->whatsapp) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $setting->email) }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $setting->address) }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Google Maps Embed</label>
                                <textarea name="maps_embed" class="form-control" rows="3">{{ old('maps_embed', $setting->maps_embed) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Rekening Pembayaran</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank</label>
                                <input type="text" name="bank_name" class="form-control"
                                    value="{{ old('bank_name', $setting->bank_name) }}" placeholder="Contoh: BCA">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Rekening</label>
                                <input type="text" name="bank_account_name" class="form-control"
                                    value="{{ old('bank_account_name', $setting->bank_account_name) }}"
                                    placeholder="Contoh: Dapur Kita">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" name="bank_account_number" class="form-control"
                                    value="{{ old('bank_account_number', $setting->bank_account_number) }}"
                                    placeholder="Contoh: 1234567890">
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Sosial Media</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="instagram" class="form-control"
                                    value="{{ old('instagram', $setting->instagram) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tiktok</label>
                                <input type="text" name="tiktok" class="form-control"
                                    value="{{ old('tiktok', $setting->tiktok) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="facebook" class="form-control"
                                    value="{{ old('facebook', $setting->facebook) }}">
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Operasional & Transaksi</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jam Buka</label>
                                <input type="time" name="open_time" class="form-control"
                                    value="{{ old('open_time', $setting->open_time) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jam Tutup</label>
                                <input type="time" name="close_time" class="form-control"
                                    value="{{ old('close_time', $setting->close_time) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Pajak %</label>
                                <input type="number" step="0.01" name="tax_percent" class="form-control"
                                    value="{{ old('tax_percent', $setting->tax_percent) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Service %</label>
                                <input type="number" step="0.01" name="service_percent" class="form-control"
                                    value="{{ old('service_percent', $setting->service_percent) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ongkos Kirim</label>
                                <input type="number" step="0.01" name="shipping_cost" class="form-control"
                                    value="{{ old('shipping_cost', $setting->shipping_cost) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" name="currency" class="form-control"
                                    value="{{ old('currency', $setting->currency ?? 'IDR') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Timezone</label>
                                <input type="text" name="timezone" class="form-control"
                                    value="{{ old('timezone', $setting->timezone ?? 'Asia/Jakarta') }}">
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="is_open" value="1" class="form-check-input"
                                        {{ old('is_open', $setting->is_open) ? 'checked' : '' }}>
                                    <label class="form-check-label">Toko Buka</label>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="maintenance_mode" value="1"
                                        class="form-check-input"
                                        {{ old('maintenance_mode', $setting->maintenance_mode) ? 'checked' : '' }}>
                                    <label class="form-check-label">Maintenance Mode</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Tampilan & SEO</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Theme Color</label>
                                <input type="text" name="theme_color" class="form-control" placeholder="#ff6600"
                                    value="{{ old('theme_color', $setting->theme_color) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Hero Title</label>
                                <input type="text" name="hero_title" class="form-control"
                                    value="{{ old('hero_title', $setting->hero_title) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ old('meta_title', $setting->meta_title) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hero Subtitle</label>
                                <textarea name="hero_subtitle" class="form-control" rows="3">{{ old('hero_subtitle', $setting->hero_subtitle) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <textarea name="meta_keywords" class="form-control" rows="2">{{ old('meta_keywords', $setting->meta_keywords) }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
