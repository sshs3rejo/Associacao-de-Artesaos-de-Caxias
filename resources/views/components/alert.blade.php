@props(['type' => 'success', 'message' => null, 'dismissible' => true])
@php
    $colors = [
        'success' => 'bg-green-100 text-green-800 border-green-200',
        'error' => 'bg-red-100 text-red-700 border-red-400',
        'info' => 'bg-blue-100 text-blue-800 border-blue-200',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    ];
    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'info' => 'fa-info-circle',
        'warning' => 'fa-exclamation-triangle',
    ];
    $color = $colors[$type] ?? $colors['success'];
    $icon = $icons[$type] ?? $icons['success'];
@endphp
@if($message)
    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
         class="flex items-center gap-2 {{ $color }} px-4 py-3 rounded-lg border mb-4" role="alert">
        <i class="fas {{ $icon }}"></i>
        <span class="flex-1">{{ $message }}</span>
        @if($dismissible)
            <button @click="show = false" class="text-current hover:opacity-70 text-xl leading-none">&times;</button>
        @endif
    </div>
@endif
