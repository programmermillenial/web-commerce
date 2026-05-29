@extends('admin.layouts.app')

@section('title', 'Change Password')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Change Password</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('change-password.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror">

                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror">

                        @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
