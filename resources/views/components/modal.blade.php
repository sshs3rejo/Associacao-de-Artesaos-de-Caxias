@props(['name', 'show' => false, 'maxWidth' => '2xl'])

@php
$modalId = $name ?? 'default-modal';
$maxWidthClass = match ($maxWidth) {
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
    default => '',
};
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" @if($show) data-bs-show="true" @endif>
    <div class="modal-dialog modal-dialog-centered {{ $maxWidthClass }}">
        <div class="modal-content border-0 shadow">
            {{ $slot }}
        </div>
    </div>
</div>

@if($show)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('{{ $modalId }}'));
        modal.show();
    });
</script>
@endif
