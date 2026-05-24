@props(['type' => 'success', 'size' => 'sm'])
@php
    $colors = [
        'success' => 'bg-green-500 text-white',
        'pending' => 'bg-yellow-400 text-gray-900',
        'inactive' => 'bg-gray-500 text-white',
        'info' => 'bg-blue-400 text-white',
        'danger' => 'bg-red-500 text-white',
        'soft-success' => 'bg-green-100 text-green-800',
        'soft-pending' => 'bg-yellow-100 text-yellow-800',
        'soft-danger' => 'bg-red-100 text-red-800',
        'welcome' => 'bg-blue-400 text-white',
    ];
    $color = $colors[$type] ?? 'bg-green-500 text-white';
    $sizes = ['sm' => 'px-2 py-0.5 text-xs', 'md' => 'px-3 py-1 text-sm', 'lg' => 'px-4 py-1.5 text-sm'];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
@endphp
<span class="inline-flex items-center {{ $sizeClass }} rounded-full font-semibold {{ $color }} {{ $attributes->get('class') }}"
      {{ $attributes->except('class') }}>
    {{ $slot }}
</span>
