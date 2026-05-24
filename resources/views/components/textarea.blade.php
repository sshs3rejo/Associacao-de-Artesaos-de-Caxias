@props(['name', 'label' => null, 'placeholder' => '', 'value' => '', 'required' => false, 'rows' => 4, 'disabled' => false, 'error' => null, 'id' => null])
@php $id = $id ?? $name; @endphp
<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block font-bold mb-1 text-brand">{{ $label }}</label>
    @endif
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if($required) required @endif
              @if($disabled) disabled @endif
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error($name) border-red-500 @enderror {{ $attributes->get('class') }}"
              {{ $attributes->except('class') }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
