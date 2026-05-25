@props(['editRoute' => null, 'deleteRoute' => null, 'deleteMessage' => 'Tem certeza?', 'approveRoute' => null, 'rejectRoute' => null, 'showLabel' => null, 'showRoute' => null])
<div class="flex gap-2 mt-3 pt-2 border-t border-gray-200">
    @if($editRoute)
        <a href="{{ $editRoute }}" class="flex-1 text-center px-3 py-1.5 text-sm border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-400 hover:text-white transition no-underline">
            <x-icon name="pencil" class="w-3 h-3 inline" /> Editar
        </a>
    @endif
    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="flex-1 m-0 p-0">
            @csrf @method('DELETE')
            <button type="button" class="w-full px-3 py-1.5 text-sm border border-red-400 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition cursor-pointer" onclick="var f=this.closest('form'); showConfirm('{{ $deleteMessage }}',function(){f.submit();}); return false;">
                <x-icon name="trash" class="w-3 h-3 inline" /> Remover
            </button>
        </form>
    @endif
    @if($showRoute)
        <a href="{{ $showRoute }}" class="flex-1 text-center px-3 py-1.5 text-sm border border-blue-400 text-blue-700 rounded-lg hover:bg-blue-400 hover:text-white transition no-underline">
            <x-icon name="eye" class="w-3 h-3 inline" /> {{ $showLabel ?? 'Ver' }}
        </a>
    @endif
    @if($approveRoute)
        <form action="{{ $approveRoute }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-green-500 hover:bg-green-600 rounded-lg font-semibold transition no-underline text-center border-0 cursor-pointer">
                <x-icon name="check" class="w-3 h-3" /> Aprovar
            </button>
        </form>
    @endif
    @if($rejectRoute)
        <form action="{{ $rejectRoute }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-red-500 hover:bg-red-600 rounded-lg font-semibold transition no-underline text-center border-0 cursor-pointer" onclick="var f=this.closest('form'); showConfirm('Rejeitar?',function(){f.submit();}); return false;">
                <x-icon name="times" class="w-3 h-3" /> Rejeitar
            </button>
        </form>
    @endif
</div>
