@extends('layouts.main')

@section('titulo', 'Painel Administrativo')


@section('content')
<div class="w-full px-4 py-4">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel Administrativo']]" />
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1 text-brand">Painel Administrativo</h1>
            <p class="text-gray-500 mb-0">Visão geral das principais métricas do sistema.</p>
        </div>
    </div>

    {{-- Estatísticas principais --}}
    <section class="dashboard-grid mb-5">
        <article class="dashboard-card">
            <a href="{{ route('produtos') }}">
                <h3>Produtos cadastrados</h3>
                <strong>{{ $stats['produtos'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('evento') }}">
                <h3>Eventos ativos</h3>
                <strong>{{ $stats['eventos'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.vendas.index') }}">
                <h3>Vendas</h3>
                <strong>{{ $stats['vendas'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.clientes.index') }}">
                <h3>Clientes</h3>
                <strong>{{ $stats['clientes'] ?? 0 }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.artesao') }}">
                <h3>Artesãos</h3>
                <strong>{{ $stats['artesos'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.usuarios') }}">
                <h3>Administradores</h3>
                <strong>{{ $stats['usuariosAdmin'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.usuarios') }}">
                <h3>Usuários ativos</h3>
                <strong>{{ $stats['usuariosAtivos'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.oficinas.index') }}">
                <h3>Oficinas</h3>
                <strong>{{ $stats['oficinas'] ?? 0 }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.instrutores.index') }}">
                <h3>Instrutores</h3>
                <strong>{{ $stats['instrutores'] }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.fornecedores.index') }}">
                <h3>Fornecedores</h3>
                <strong>{{ $stats['fornecedores'] ?? 0 }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.materias-primas.index') }}">
                <h3>Matérias-Primas</h3>
                <strong>{{ $stats['materiasPrimas'] ?? 0 }}</strong>
            </a>
        </article>
        <article class="dashboard-card">
            <a href="{{ route('admin.contatos.index') }}">
                <h3>Contatos</h3>
                <strong>{{ $stats['contatos'] ?? 0 }}</strong>
            </a>
        </article>
    </section>

    {{-- Histórico de Vendas --}}
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <div class="data-table p-0">
                <div class="p-4 border-b">
                    <h2 class="text-lg mb-0 font-bold" style="color: #7a2f1f;">Histórico de Vendas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Cliente</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Data</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($vendas as $venda)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">#{{ $venda->id_venda }}</td>
                                <td class="px-4 py-3 text-sm">{{ $venda->cliente->nome ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($venda->status_pagamento === 'approved')
                                        <x-badge type="success">Pago</x-badge>
                                    @else
                                        <x-badge type="pending">Pendente</x-badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($venda->status_pagamento !== 'approved')
                                        <form action="{{ route('admin.vendas.aprovar', $venda->id_venda) }}" method="POST" class="inline m-0 p-0">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg font-semibold text-white shadow-sm bg-green-500 hover:bg-green-600 no-underline text-center" style="font-size: 0.75rem; border: none;">
                                                <x-icon name="check" class="w-4 h-4" /> Aprovar Pix
                                            </button>
                                        </form>
                                        @endif
                                        <button class="inline-flex items-center justify-center p-0 border border-gray-400 text-gray-600 hover:bg-gray-50 rounded-lg no-underline text-center font-semibold" title="Itens do Pedido: @foreach($venda->itens as $item) {{ $item->produto->nome }} x{{ $item->quantidade }}; @endforeach" style="width: 28px; height: 28px;">
                                            <x-icon name="info" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-gray-500 text-sm">Nenhuma venda encontrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $vendas->links() }}
            </div>
        </div>
    </div>

    <!-- Propostas de Produtos Pendentes -->
    <div class="grid grid-cols-12 gap-4 mt-8">
        <div class="col-span-12">
            <div class="data-table p-0">
                <div class="p-4 border-b flex items-center justify-between">
                    <h2 class="text-lg mb-0 font-bold" style="color: #7a2f1f;"><x-icon name="boxes" class="w-5 h-5 me-2" /> Propostas de Produtos Pendentes</h2>
                    <x-badge type="pending" size="md">{{ $produtosPendentes->total() }} aguardando</x-badge>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Miniatura</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nome</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Artesão</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Categoria</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Preço</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Estoque</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($produtosPendentes as $prodPendente)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <x-image :src="$prodPendente->imagem" :alt="$prodPendente->nome" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover;" />
                                </td>
                                <td class="px-4 py-3 text-sm font-bold" style="color: #8b5a3c;">{{ $prodPendente->nome }}</td>
                                <td class="px-4 py-3 text-sm">{{ $prodPendente->artisan?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $prodPendente->categoria?->nome_categoria ?? 'Sem Categoria' }}</td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">R$ {{ number_format($prodPendente->preco, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $prodPendente->estoque?->quantidade ?? 0 }} unid.</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <x-card-actions :approve-route="route('admin.produtos.aprovar', $prodPendente->id_produto)" :reject-route="route('admin.produtos.rejeitar', $prodPendente->id_produto)" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-gray-500 text-sm">Nenhum produto pendente de aprovação.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-t">
                    {{ $produtosPendentes->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Propostas de Eventos Pendentes -->
    <div class="grid grid-cols-12 gap-4 mt-8">
        <div class="col-span-12">
            <div class="data-table p-0">
                <div class="p-4 border-b flex items-center justify-between">
                    <h2 class="text-lg mb-0 font-bold" style="color: #7a2f1f;"><x-icon name="calendar" class="w-5 h-5 me-2" /> Propostas de Eventos Pendentes</h2>
                    <x-badge type="pending" size="md">{{ $eventosPendentes->total() }} aguardando</x-badge>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Capa</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nome</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Proponente</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Tipo</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Data Início</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Local</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Preço</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($eventosPendentes as $evPendente)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <x-image src="{{ $evPendente->imagem }}" alt="{{ $evPendente->nome }}" fallback="{{ config('association.placeholder') }}" class="rounded shadow-sm" style="width: 55px; height: 40px; object-fit: cover;" />
                                </td>
                                <td class="px-4 py-3 text-sm font-bold" style="color: #8b5a3c;">{{ $evPendente->nome }}</td>
                                <td class="px-4 py-3 text-sm">{{ $evPendente->artisan?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm"><x-badge type="inactive">{{ ucfirst($evPendente->tipo_evento) }}</x-badge></td>
                                <td class="px-4 py-3 text-sm">{{ $evPendente->data_inicio?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $evPendente->local }}</td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">{{ $evPendente->isGratuito() ? 'Gratuito' : 'R$ ' . number_format($evPendente->valor_inscricao, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <x-card-actions :approve-route="route('admin.eventos.aprovar', $evPendente->id_evento)" :reject-route="route('admin.eventos.rejeitar', $evPendente->id_evento)" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-gray-500 text-sm">Nenhum evento pendente de aprovação.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-t">
                    {{ $eventosPendentes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
