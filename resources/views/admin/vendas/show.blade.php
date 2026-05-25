@extends('layouts.main')
@section('titulo', "Venda #{$venda->id_venda}")

@section('content')
<div class="max-w-5xl mx-auto my-10 px-4">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Vendas', route('admin.vendas.index')], ['#'.$venda->id_venda]]" />

    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-brand">Venda #{{ $venda->id_venda }}</h1>
            <a href="{{ route('admin.vendas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4" /> Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente</h3>
                <p class="text-gray-800 font-semibold">{{ $venda->cliente?->nome ?? 'N/A' }}</p>
                @if($venda->cliente?->email)
                    <p class="text-sm text-gray-500">{{ $venda->cliente->email }}</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor Total</h3>
                <p class="text-gray-800 text-xl font-bold">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Status do Pagamento</h3>
                <p class="mt-1">
                    @if($venda->status_pagamento === 'approved')
                        <x-badge type="success">Aprovado</x-badge>
                    @elseif($venda->status_pagamento === 'rejected')
                        <x-badge type="danger">Rejeitado</x-badge>
                    @else
                        <x-badge type="pending">Pendente</x-badge>
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Data</h3>
                <p class="text-gray-800">{{ \Carbon\Carbon::parse($venda->created_at)->format('d/m/Y H:i') }}</p>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-bold text-brand mb-4">Itens da Venda</h2>

        @if($venda->itensVenda->isEmpty())
            <p class="text-gray-500">Nenhum item nesta venda.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Produto</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Quantidade</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Preço Unit.</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $subtotal = 0; @endphp
                        @foreach($venda->itensVenda as $item)
                            @php $itemSub = $item->quantidade * $item->preco_unitario; $subtotal += $itemSub; @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold">{{ $item->produto?->nome ?? 'Produto #'.$item->id_produto }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->quantidade }}</td>
                                <td class="px-4 py-3 text-sm">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">R$ {{ number_format($itemSub, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-sm font-bold text-right text-gray-700">Total</td>
                            <td class="px-4 py-3 text-sm font-bold text-brand">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
