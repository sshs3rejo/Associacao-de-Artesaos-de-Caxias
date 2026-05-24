@props(['route' => null, 'label' => 'Voltar'])
@if($route)
    <a href="{{ $route }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
        <i class="fas fa-arrow-left text-xs"></i> {{ $label }}
    </a>
@endif
