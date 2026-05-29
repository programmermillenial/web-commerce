@extends('admin.layouts.app')

@section('title', 'Edit Voucher')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Edit Voucher</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.vouchers.form')

                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Update
                    </button>

                    <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
