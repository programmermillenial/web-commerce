<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kode Voucher</label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $voucher->code ?? '') }}" placeholder="Contoh: HEMAT10">

        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Voucher</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $voucher->name ?? '') }}" placeholder="Contoh: Diskon 10 Ribu">

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tipe Voucher</label>
        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
            <option value="">Pilih Tipe</option>
            <option value="fixed" {{ old('type', $voucher->type ?? '') == 'fixed' ? 'selected' : '' }}>
                Fixed / Nominal
            </option>
            <option value="percent" {{ old('type', $voucher->type ?? '') == 'percent' ? 'selected' : '' }}>
                Percent
            </option>
            <option value="free_shipping" {{ old('type', $voucher->type ?? '') == 'free_shipping' ? 'selected' : '' }}>
                Free Shipping
            </option>
        </select>

        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3" id="value-wrapper">
        <label class="form-label" id="value-label">Value</label>
        <input type="text" id="value_display" class="form-control @error('value') is-invalid @enderror"
            value="{{ old('value', $voucher->value ?? 0) }}" placeholder="Masukkan value">

        <input type="hidden" name="value" id="value" value="{{ old('value', $voucher->value ?? 0) }}">

        @error('value')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Minimum Order</label>
        <input type="text" id="minimum_order_display" class="form-control"
            value="{{ old('minimum_order', $voucher->minimum_order ?? 0) }}">

        <input type="hidden" name="minimum_order" id="minimum_order"
            value="{{ old('minimum_order', $voucher->minimum_order ?? 0) }}">

        @error('minimum_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3" id="maximum-discount-wrapper">
        <label class="form-label">Maximum Discount</label>
        <input type="number" name="maximum_discount"
            class="form-control @error('maximum_discount') is-invalid @enderror"
            value="{{ old('maximum_discount', $voucher->maximum_discount ?? '') }}"
            placeholder="Khusus percent, boleh kosong">

        @error('maximum_discount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Quota</label>
        <input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror"
            value="{{ old('quota', $voucher->quota ?? '') }}" placeholder="Kosongkan jika unlimited">

        @error('quota')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', isset($voucher) && $voucher->start_date ? $voucher->start_date->format('Y-m-d\TH:i') : '') }}">

        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Berakhir</label>
        <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', isset($voucher) && $voucher->end_date ? $voucher->end_date->format('Y-m-d\TH:i') : '') }}">

        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Aktif</label>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function toggleVoucherInput() {
            let type = $('#type').val();

            if (type === 'free_shipping') {
                $('#value-wrapper').hide();
                $('#maximum-discount-wrapper').hide();
                $('#value').val(0);
                $('#value_display').val('');
            }

            if (type === 'fixed') {
                $('#value-wrapper').show();
                $('#maximum-discount-wrapper').hide();
                $('#value-label').text('Nominal Diskon');
                $('#value_display').attr('placeholder', 'Contoh: 10.000');
            }

            if (type === 'percent') {
                $('#value-wrapper').show();
                $('#maximum-discount-wrapper').show();
                $('#value-label').text('Persentase Diskon');
                $('#value_display').attr('placeholder', 'Contoh: 10');
            }
        }

        function formatRupiah(value) {
            value = value.toString().replace(/[^,\d]/g, '');
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function initFormattedInput(displaySelector, hiddenSelector) {
            let display = $(displaySelector);
            let hidden = $(hiddenSelector);
            let raw = hidden.val();

            if (raw) {
                display.val(formatRupiah(raw));
            }

            display.on('input', function() {
                let rawValue = $(this).val().replace(/[^\d]/g, '');
                hidden.val(rawValue);
                if (rawValue === '') {
                    $(this).val('');
                    return;
                }
                $(this).val(formatRupiah(rawValue));
            });
        }

        $('#type').on('change', function() {
            $('#value').val('');
            $('#value_display').val('');

            toggleVoucherInput();
        });

        initFormattedInput('#value_display', '#value');
        initFormattedInput('#minimum_order_display', '#minimum_order');

        toggleVoucherInput();
    </script>
@endpush
