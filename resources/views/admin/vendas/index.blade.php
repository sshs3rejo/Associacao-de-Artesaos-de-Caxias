@extends('layouts.main')
@section('titulo', 'Vendas')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Vendas']]" />
    <h1 class="font-bold mb-4 text-2xl text-brand">Vendas</h1>

    @if($vendas->isEmpty())
        <div class="text-center py-5">
            <x-icon name="cart" class="w-12 h-12 text-gray-500 mb-3 block" />
            <p class="text-gray-500 text-lg">Nenhuma venda encontrada.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">#</th>
                        <th class="p-3 text-left text-sm font-semibold">Cliente</th>
                        <th class="p-3 text-left text-sm font-semibold">Total</th>
                        <th class="p-3 text-left text-sm font-semibold">Status</th>
                        <th class="p-3 text-left text-sm font-semibold">Data</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($vendas as $v)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">#{{ $v->id_venda }}</td>
                            <td class="p-3 text-sm">{{ $v->cliente?->nome ?? 'N/A' }}</td>
                            <td class="p-3 text-sm font-semibold">R$ {{ number_format($v->valor_total, 2, ',', '.') }}</td>
                            <td class="p-3 text-sm">
                                @if($v->status_pagamento === 'approved')
                                    <x-badge type="success">Pago</x-badge>
                                @elseif($v->status_pagamento === 'rejected')
                                    <x-badge type="danger">Rejeitado</x-badge>
                                @else
                                    <x-badge type="pending">Pendente</x-badge>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.vendas.show', $v->id_venda) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-blue-400 text-blue-600 hover:bg-blue-50 text-sm">
                                        <x-icon name="eye" class="w-4 h-4" /> Ver
                                    </a>
                                    @if($v->status_pagamento !== 'approved')
                                        <form action="{{ route('admin.vendas.destroy', $v->id_venda) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir a venda #{{ $v->id_venda }}?',function(){f.submit();}); return false;">
                                                <x-icon name="trash" class="w-4 h-4" /> Excluir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $vendas->links() }}
        </div>
    @endif
</div>
@endsection
