@props(['align' => 'right'])

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </div>
    <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-3">
        {{ $content }}
    </div>
</div>
