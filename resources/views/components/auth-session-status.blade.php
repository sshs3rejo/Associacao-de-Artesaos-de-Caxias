@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success alert-dismissible fade show d-flex align-items-center']) }} role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ $status }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
