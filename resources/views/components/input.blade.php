@props(['name', 'label' => null, 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false, 'disabled' => false, 'readonly' => false, 'error' => null, 'help' => null, 'id' => null])
@php $id = $id ?? $name; @endphp
<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block font-bold mb-1 text-brand">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           @if($disabled) disabled @endif
           @if($readonly) readonly @endif
           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error($name) border-red-500 @enderror {{ $attributes->get('class') }}"
           {{ $attributes->except('class') }}>
    @error($name)
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
    @if($help)
        <div class="text-gray-400 text-xs mt-1">{{ $help }}</div>
    @endif
</div>
