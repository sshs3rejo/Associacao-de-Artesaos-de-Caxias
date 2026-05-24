@props(['id', 'title' => '', 'size' => 'md'])
@php
    $sizeClass = $size === 'lg' ? 'modal-lg' : '';
@endphp
<div id="{{ $id }}" class="modal-overlay {{ $sizeClass }}" onclick="if(event.target===this)hideModal('{{ $id }}')">
    <div>
        <div class="flex items-center justify-between px-6 pt-6 pb-0">
            <h5 class="text-xl font-bold text-brand m-0">{{ $title }}</h5>
            <button onclick="hideModal('{{ $id }}')" class="text-3xl text-gray-400 hover:text-gray-600 leading-none bg-transparent border-0 cursor-pointer">&times;</button>
        </div>
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
