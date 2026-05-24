@props(['src' => null, 'alt' => '', 'fallback' => null, 'class' => '', 'loading' => 'lazy'])
@php
    $fallback = $fallback ?? config('association.placeholder');
    if (!$src) { $imgSrc = $fallback; $webpSrc = null; }
    elseif (str_starts_with($src, 'http') || str_starts_with($src, 'data:')) { $imgSrc = $src; $webpSrc = null; }
    elseif (str_starts_with($src, 'imagens/')) { $imgSrc = asset($src); $webpSrc = null; }
    else {
        $imgSrc = asset('storage/' . $src);
        $webpPath = public_path('storage/' . preg_replace('/\.(png|jpe?g)$/i', '.webp', $src));
        $webpSrc = file_exists($webpPath) ? asset('storage/' . preg_replace('/\.(png|jpe?g)$/i', '.webp', $src)) : null;
    }
@endphp
@if($webpSrc)
<picture>
    <source srcset="{{ $webpSrc }}" type="image/webp">
    <img src="{{ $imgSrc }}" alt="{{ $alt }}" loading="{{ $loading }}" class="{{ $class }} {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
</picture>
@else
<img src="{{ $imgSrc }}" alt="{{ $alt }}" loading="{{ $loading }}" class="{{ $class }} {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
@endif
