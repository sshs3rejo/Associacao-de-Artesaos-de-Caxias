@props(['route' => null, 'label' => 'Voltar'])
@if($route)
    <a href="{{ $route }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3 no-underline">
        <x-icon name="arrow-left" class="w-3 h-3" /> {{ $label }}
    </a>
@endif
