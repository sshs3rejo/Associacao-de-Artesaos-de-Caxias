@props(['active'])

@php
$classes = ($active ?? false)
            ? 'dropdown-item fw-semibold active'
            : 'dropdown-item';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
