@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link fw-semibold active'
            : 'nav-link fw-semibold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
