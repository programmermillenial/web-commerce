@extends('admin.layouts.app')

@section('title', 'Tambah Voucher')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Tambah Voucher</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('vouchers.store') }}" method="POST">
                @csrf

                @include('admin.vouchers.form')

                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Simpan
                    </button>

                    <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
