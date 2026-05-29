@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show flash-message">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('errorhtml'))
    <div class="alert alert-danger alert-solid alert-dismissible fade show" role="alert">
        <span>
            <i class="fas fa-times-circle me-2"></i>
            {!! session('errorhtml') !!}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-solid alert-dismissible fade show" role="alert">
        <span>
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-solid alert-dismissible fade show" role="alert">
        <span>
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-solid alert-dismissible fade show" role="alert">
        <div class="d-flex flex-column">
            <strong class="mb-1">
                <i class="fas fa-times-circle me-2"></i>
                Validation Error
            </strong>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
