@props(['name', 'label' => null, 'options' => [], 'value' => '', 'required' => false, 'disabled' => false, 'placeholder' => 'Selecione...', 'error' => null, 'id' => null])
@php $id = $id ?? $name; @endphp
<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block font-bold mb-1 text-brand">{{ $label }}</label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error($name) border-red-500 @enderror {{ $attributes->get('class') }}"
            {{ $attributes->except('class') }}>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
