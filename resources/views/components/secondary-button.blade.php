<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary px-4 py-2']) }}>
    {{ $slot }}
</button>
