@extends('layouts.main')
@section('titulo', 'Compras de Matéria-Prima')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Compras de Matéria-Prima']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Compras de Matéria-Prima</h1>
        <a href="{{ route('admin.compras-materia-prima.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <x-icon name="plus" class="w-4 h-4" /> Nova Compra
        </a>
    </div>

    @if($compras->isEmpty())
        <div class="text-center py-5">
            <x-icon name="cart" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
            <p class="text-gray-500 text-lg">Nenhuma compra de matéria-prima registrada ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Fornecedor</th>
                        <th class="p-3 text-left text-sm font-semibold">Matéria-Prima</th>
                        <th class="p-3 text-left text-sm font-semibold">Quantidade</th>
                        <th class="p-3 text-left text-sm font-semibold">Preço Unitário</th>
                        <th class="p-3 text-left text-sm font-semibold">Total</th>
                        <th class="p-3 text-left text-sm font-semibold">Data da Compra</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($compras as $compra)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $compra->fornecedor->nome }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $compra->materiaPrima->nome }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $compra->quantidade }}</td>
                            <td class="p-3 text-sm text-gray-500">R$ {{ number_format($compra->preco_unitario, 2, ',', '.') }}</td>
                            <td class="p-3 text-sm font-semibold">R$ {{ number_format($compra->quantidade * $compra->preco_unitario, 2, ',', '.') }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $compra->data_compra ? \Carbon\Carbon::parse($compra->data_compra)->format('d/m/Y') : '—' }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.compras-materia-prima.edit', $compra->id_compra) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                        <x-icon name="pencil" class="w-4 h-4" /> Editar
                                    </a>
                                    <form action="{{ route('admin.compras-materia-prima.destroy', $compra->id_compra) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir esta compra?',function(){f.submit();}); return false;">
                                            <x-icon name="trash" class="w-4 h-4" /> Remover
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
