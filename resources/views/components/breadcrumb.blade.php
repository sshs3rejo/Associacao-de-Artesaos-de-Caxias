@props(['items' => []])
<div class="breadcrumb">
    @foreach($items as $item)
        @if(!$loop->first)
            <span>/</span>
        @endif
        @if(isset($item[1]) && $item[1])
            <a href="{{ $item[1] }}">{{ $item[0] }}</a>
        @else
            <span>{{ $item[0] }}</span>
        @endif
    @endforeach
</div>
