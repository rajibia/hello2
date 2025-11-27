<div class="footer py-4 d-flex flex-lg-column position-sticky bottom-0">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="text-muted d-flex align-items-center gap-2 flex-wrap">
            <span class="me-2">All Rights Reserved</span>
            <span class="text-muted fw-bold">&copy; {{ date('Y') }}</span>
            <a data-turbo="false" href="{{ url('/') }}" class="text-hover-primary ms-2">{{ config('app.name') }}</a>
        </div>
        <div class="text-muted order-2 order-md-1">
            @if(env('VERSION_NUMBER'))
                <span class="d-inline-block px-3 py-2 rounded-2">v{{ getCurrentVersion() }}</span>
            @endif
        </div>
    </div>
</div>
