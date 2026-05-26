@extends('layouts.main')
@section('titulo', 'Nova Venda')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Nova Venda</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-700 hover:text-red-900 cursor-pointer border-0 bg-transparent">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vendas.store') }}" method="POST" id="form-venda">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-1 text-brand text-sm">Cliente</label>
            <select name="id_cliente" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" required>
                <option value="">Selecione um cliente</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->id_cliente }}">{{ $c->nome }} {{ $c->email ? '- ' . $c->email : '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-brand text-sm">Itens da Venda</label>
            <div id="itens-container">
                <div class="item-row flex gap-2 mb-2">
                    <select name="itens[0][id_produto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" required>
                        <option value="">Selecione um produto</option>
                        @foreach($produtos as $p)
                            <option value="{{ $p->id_produto }}">{{ $p->nome }} - R$ {{ number_format($p->preco, 2, ',', '.') }} (Estoque: {{ $p->estoque?->quantidade ?? 0 }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="itens[0][quantidade]" min="1" value="1" class="w-24 border border-gray-300 rounded-lg px-3 py-3 text-center text-sm" required>
                    <button type="button" onclick="this.closest('.item-row').remove()" class="px-3 py-2 text-red-500 hover:text-red-700 border-0 bg-transparent cursor-pointer">
                        <i class="fas fa-times" style="font-size:18px"></i>
                    </button>
                </div>
            </div>
            <button type="button" onclick="adicionarItem()" class="mt-2 text-sm text-brand hover:text-brand-light font-semibold border-0 bg-transparent cursor-pointer flex items-center gap-1">
                <i class="fas fa-plus" style="font-size:14px"></i> Adicionar item
            </button>
        </div>

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.vendas.index')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">
                <i class="fas fa-check-circle" style="font-size:16px"></i> Registrar Venda
            </button>
        </div>
    </form>
</div>

@php
    $produtosJson = $produtos->map(fn($p) => [
        'id' => $p->id_produto,
        'nome' => $p->nome,
        'preco' => $p->preco,
        'estoque' => $p->estoque?->quantidade ?? 0,
    ])->toArray();
@endphp
<script>
window._produtosVenda = @json($produtosJson);
</script>
@endsection

@section('scripts')
<script>
let itemIndex = 1;
function adicionarItem() {
    const container = document.getElementById('itens-container');
    const div = document.createElement('div');
    div.className = 'item-row flex gap-2 mb-2';
    const produtos = window._produtosVenda || [];
    let options = '<option value="">Selecione um produto</option>';
    produtos.forEach(function(p) {
        const label = p.nome + ' - R$ ' + p.preco.toFixed(2).replace('.', ',') + ' (Estoque: ' + p.estoque + ')';
        options += '<option value="' + p.id + '">' + label + '</option>';
    });
    div.innerHTML = `
        <select name="itens[${itemIndex}][id_produto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" required>
            ${options}
        </select>
        <input type="number" name="itens[${itemIndex}][quantidade]" min="1" value="1" class="w-24 border border-gray-300 rounded-lg px-3 py-3 text-center text-sm" required>
        <button type="button" onclick="this.closest('.item-row').remove()" class="px-3 py-2 text-red-500 hover:text-red-700 border-0 bg-transparent cursor-pointer">
            <i class="fas fa-times" style="font-size:18px"></i>
        </button>
    `;
    container.appendChild(div);
    itemIndex++;
}
</script>
@endsection