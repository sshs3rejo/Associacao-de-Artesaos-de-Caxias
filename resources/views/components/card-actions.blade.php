@props(['editRoute' => null, 'deleteRoute' => null, 'deleteMessage' => 'Tem certeza?', 'approveRoute' => null, 'rejectRoute' => null, 'showLabel' => null, 'showRoute' => null])
<div class="flex gap-2 mt-3 pt-2 border-t border-gray-200">
    @if($editRoute)
        <a href="{{ $editRoute }}" class="flex-1 text-center px-3 py-1.5 text-sm border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-400 hover:text-white transition no-underline">
            <i class="fa fa-pencil"></i> Editar
        </a>
    @endif
    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="flex-1 m-0 p-0" onsubmit="return confirm('{{ $deleteMessage }}')">
            @csrf @method('DELETE')
            <button type="submit" class="w-full px-3 py-1.5 text-sm border border-red-400 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition">
                <i class="fa fa-trash"></i> Remover
            </button>
        </form>
    @endif
    @if($showRoute)
        <a href="{{ $showRoute }}" class="flex-1 text-center px-3 py-1.5 text-sm border border-blue-400 text-blue-700 rounded-lg hover:bg-blue-400 hover:text-white transition no-underline">
            <i class="fa fa-eye"></i> {{ $showLabel ?? 'Ver' }}
        </a>
    @endif
    @if($approveRoute)
        <form action="{{ $approveRoute }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-green-500 hover:bg-green-600 rounded-lg font-semibold transition no-underline text-center border-0 cursor-pointer">
                <i class="fas fa-check"></i> Aprovar
            </button>
        </form>
    @endif
    @if($rejectRoute)
        <form action="{{ $rejectRoute }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Rejeitar?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-red-500 hover:bg-red-600 rounded-lg font-semibold transition no-underline text-center border-0 cursor-pointer">
                <i class="fas fa-times"></i> Rejeitar
            </button>
        </form>
    @endif
</div>
