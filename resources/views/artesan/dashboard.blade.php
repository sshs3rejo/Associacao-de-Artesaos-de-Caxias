@extends('layouts.main')

@section('titulo', 'Dashboard - Artesão')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Dashboard Artesão']]" />
    <div class="flex items-center gap-3 mb-5">
        <div class="rounded-full p-3 shadow-sm" style="background-color: #F9F7D3;">
            <i class="fas fa-user-tie text-3xl" style="color: #7a2f1f;"></i>
        </div>
        <div>
            <h1 class="font-bold mb-1 text-2xl" style="color: #7a2f1f;">Olá, {{ $user->name }}!</h1>
            <p class="text-gray-500 mb-0">
                @if($perfil && $perfil->isApproved())
                    <x-badge type="success">Perfil aprovado</x-badge>
                @else
                    <x-badge type="pending">Aguardando aprovação</x-badge>
                @endif
            </p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 mb-5">
        <div class="md:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow-sm h-full">
                <div class="text-center p-4">
                    <i class="fas fa-box-open text-5xl mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="text-base font-bold">{{ $totalProdutos }}</h3>
                    <p class="text-gray-500 text-sm mb-0">Meus Produtos</p>
                </div>
            </div>
        </div>
        <div class="md:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow-sm h-full">
                <div class="text-center p-4">
                    <i class="fas fa-calendar-check text-5xl mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="text-base font-bold">{{ $eventosInscritos }}</h3>
                    <p class="text-gray-500 text-sm mb-0">Eventos Inscritos</p>
                </div>
            </div>
        </div>
        <div class="md:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow-sm h-full">
                <div class="text-center p-4">
                    <i class="fas fa-star text-5xl mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="text-base font-bold">{{ $perfil && $perfil->is_public ? 'Público' : 'Privado' }}</h3>
                    <p class="text-gray-500 text-sm mb-0">Status do Perfil</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <div class="md:col-span-4 col-span-12">
            <a href="{{ route('produtos') }}" class="no-underline">
                <div class="bg-white rounded-xl shadow-sm h-full transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-boxes text-4xl" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="text-sm font-bold mb-1" style="color: #7a2f1f;">Meus Produtos</h4>
                                <p class="text-gray-500 text-sm mb-0">Gerenciar produtos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="md:col-span-4 col-span-12">
            <a href="{{ route('evento') }}" class="no-underline">
                <div class="bg-white rounded-xl shadow-sm h-full transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-calendar-alt text-4xl" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="text-sm font-bold mb-1" style="color: #7a2f1f;">Meus Eventos</h4>
                                <p class="text-gray-500 text-sm mb-0">Inscrições em eventos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="md:col-span-4 col-span-12">
            <a href="{{ route('artesan.perfil') }}" class="no-underline">
                <div class="bg-white rounded-xl shadow-sm h-full transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-cog text-4xl" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="text-sm font-bold mb-1" style="color: #7a2f1f;">Meu Perfil</h4>
                                <p class="text-gray-500 text-sm mb-0">Editar perfil público</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Histórico de Vendas Próprias -->
    <div class="grid grid-cols-12 gap-4 mt-8">
        <div class="col-span-12">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-white py-3 border-b px-4">
                    <h5 class="font-bold mb-0" style="color: #7a2f1f;"><i class="fas fa-receipt me-2"></i> Registro de Vendas dos Meus Produtos</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pedido</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Produto</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Qtd</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Preço Unitário</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Comprador</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Contato</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($minhasVendas as $itemVenda)
                            @if($itemVenda->venda)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">#{{ $itemVenda->venda->id_venda }}</td>
                                <td class="px-4 py-3 text-sm font-bold" style="color: #8b5a3c;">{{ $itemVenda->produto->nome ?? 'Produto Excluído' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $itemVenda->quantidade }}</td>
                                <td class="px-4 py-3 text-sm">R$ {{ number_format($itemVenda->preco_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm font-bold" style="color: #c85a3a;">R$ {{ number_format($itemVenda->preco_unitario * $itemVenda->quantidade, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $itemVenda->venda->cliente->nome ?? 'Visitante' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($itemVenda->venda->cliente && $itemVenda->venda->cliente->telefone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $itemVenda->venda->cliente->telefone) }}" target="_blank" class="inline-block px-2 py-0 rounded-full font-semibold text-center no-underline border border-green-500 text-green-600 hover:bg-green-50 text-xs">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($itemVenda->venda->mp_status === 'approved')
                                        <x-badge type="success">Pago</x-badge>
                                    @else
                                        <x-badge type="pending">Aguardando</x-badge>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-gray-500 text-sm">Você ainda não registrou nenhuma venda de seus produtos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($minhasVendas->hasPages())
                <div class="bg-white border-0 py-3 px-4 border-t">
                    {{ $minhasVendas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
