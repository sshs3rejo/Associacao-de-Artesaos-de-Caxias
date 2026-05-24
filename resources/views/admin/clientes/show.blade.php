@extends('layouts.main')
@section('titulo', "Cliente: {$cliente->nome}")

@section('content')
<div class="max-w-5xl mx-auto my-10 px-4">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Clientes', route('admin.clientes.index')], [$cliente->nome]]" />

    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-brand">{{ $cliente->nome }}</h1>
            <a href="{{ route('admin.clientes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</h3>
                <p class="text-gray-800">{{ $cliente->email ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Telefone</h3>
                <p class="text-gray-800">{{ $cliente->telefone ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Usuário Vinculado</h3>
                @if($cliente->user)
                    <p class="text-gray-800">{{ $cliente->user->name }} ({{ $cliente->user->email }})</p>
                    <x-badge :type="$cliente->user->isActive() ? 'success' : 'danger'">
                        {{ $cliente->user->isActive() ? 'Ativo' : 'Inativo' }}
                    </x-badge>
                @else
                    <p class="text-gray-400">—</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Cadastrado em</h3>
                <p class="text-gray-800">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-bold text-brand mb-4">Compras Realizadas</h2>

        @if($cliente->vendas->isEmpty())
            <p class="text-gray-500">Nenhuma compra realizada por este cliente.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Data</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Itens</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cliente->vendas as $v)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold">#{{ $v->id_venda }}</td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $v->itensVenda->count() }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">R$ {{ number_format($v->valor_total, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($v->mp_status === 'approved')
                                        <x-badge type="success">Pago</x-badge>
                                    @elseif($v->mp_status === 'rejected')
                                        <x-badge type="danger">Rejeitado</x-badge>
                                    @else
                                        <x-badge type="pending">Pendente</x-badge>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
