@props(['type' => 'success', 'message' => null, 'dismissible' => true])
@php
    $colors = [
        'success' => 'bg-green-100 text-green-800 border-green-200',
        'error' => 'bg-red-100 text-red-700 border-red-400',
        'info' => 'bg-blue-100 text-blue-800 border-blue-200',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    ];
    $iconMap = [
        'success' => 'check-circle',
        'error' => 'exclamation',
        'info' => 'info',
        'warning' => 'warning',
    ];
    $color = $colors[$type] ?? $colors['success'];
    $icon = $iconMap[$type] ?? $iconMap['success'];
@endphp
@if($message)
    <div class="flex items-center gap-2 {{ $color }} px-4 py-3 rounded-lg border mb-4" role="alert" id="alert-{{ Str::random(6) }}">
        <x-icon name="{{ $icon }}" class="w-5 h-5 shrink-0" />
        <span class="flex-1">{{ $message }}</span>
        @if($dismissible)
            <button onclick="this.closest('[id^=alert-]').remove()" class="text-current hover:opacity-70 text-xl leading-none cursor-pointer border-0 bg-transparent">&times;</button>
        @endif
    </div>
@endif
