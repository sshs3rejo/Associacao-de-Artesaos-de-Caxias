<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn fw-bold px-4 py-2']) }} style="background-color: #7a2f1f; color: #F9F7D3; border: none;">
    {{ $slot }}
</button>
