@extends('layouts.main')
@section('titulo', 'Editar Venda')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Venda #{{ $venda->id_venda }}</h1>

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

    <form action="{{ route('admin.vendas.update', $venda) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-bold mb-1 text-brand text-sm">Cliente</label>
            <select name="id_cliente" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" required>
                @foreach($clientes as $c)
                    <option value="{{ $c->id_cliente }}" {{ $venda->id_cliente == $c->id_cliente ? 'selected' : '' }}>{{ $c->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-brand text-sm">Status do Pagamento</label>
            <select name="status_pagamento" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm">
                <option value="pending" {{ $venda->status_pagamento === 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="approved" {{ $venda->status_pagamento === 'approved' ? 'selected' : '' }}>Aprovado</option>
                <option value="cancelled" {{ $venda->status_pagamento === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        @if($venda->itensVenda->isNotEmpty())
        <div class="mb-4">
            <label class="block font-bold mb-1 text-brand text-sm">Itens da Venda</label>
            <table class="w-full text-sm border rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-2 text-left">Produto</th>
                        <th class="p-2 text-center">Qtd</th>
                        <th class="p-2 text-right">Preço Unit.</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venda->itensVenda as $item)
                    <tr class="border-t">
                        <td class="p-2">{{ $item->produto?->nome ?? 'Produto removido' }}</td>
                        <td class="p-2 text-center">{{ $item->quantidade }}</td>
                        <td class="p-2 text-right">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td class="p-2 text-right">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t font-bold">
                        <td colspan="3" class="p-2 text-right">Total:</td>
                        <td class="p-2 text-right">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.vendas.index')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">
                <x-icon name="check-circle" class="w-4 h-4" /> Atualizar Venda
            </button>
        </div>
    </form>
</div>
@endsection