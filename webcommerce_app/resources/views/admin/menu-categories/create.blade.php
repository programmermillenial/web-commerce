@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Create Category
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('menu-categories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Name
                                </label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Slug
                                </label>
                                <input type="text" name="slug" class="form-control" required readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Icon
                                </label>
                                <input type="text" name="icon" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Sort Order
                                </label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Description
                                </label>
                                <textarea name="description" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" checked>
                                    <label class="form-check-label">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>

                        <a href="{{ route('menu-categories.index') }}" class="btn btn-light">
                            Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/custom/menu-category/form.js') }}"></script>
@endpush
