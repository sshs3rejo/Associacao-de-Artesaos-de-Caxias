@extends('layouts.main')

@section('titulo', 'Dashboard - Artesão')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="d-flex align-items-center gap-3 mb-5">
        <div class="rounded-circle p-3 shadow-sm" style="background-color: #F9F7D3;">
            <i class="bi bi-person-workspace fs-3" style="color: #7a2f1f;"></i>
        </div>
        <div>
            <h1 class="fw-bold mb-1" style="color: #7a2f1f;">Olá, {{ $user->name }}!</h1>
            <p class="text-muted mb-0">
                @if($perfil && $perfil->isApproved())
                    <span class="badge bg-success">Perfil aprovado</span>
                @else
                    <span class="badge bg-warning text-dark">Aguardando aprovação</span>
                @endif
            </p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-box-seam display-5 mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="h5 fw-bold">{{ $totalProdutos }}</h3>
                    <p class="text-muted small mb-0">Meus Produtos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-calendar-check display-5 mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="h5 fw-bold">{{ $eventosInscritos }}</h3>
                    <p class="text-muted small mb-0">Eventos Inscritos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-star display-5 mb-3" style="color: #7a2f1f;"></i>
                    <h3 class="h5 fw-bold">{{ $perfil && $perfil->is_public ? 'Público' : 'Privado' }}</h3>
                    <p class="text-muted small mb-0">Status do Perfil</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('artesan.produtos') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-boxes fs-1" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="h6 fw-bold mb-1" style="color: #7a2f1f;">Meus Produtos</h4>
                                <p class="text-muted small mb-0">Gerenciar produtos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('artesan.eventos') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-calendar-event fs-1" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="h6 fw-bold mb-1" style="color: #7a2f1f;">Meus Eventos</h4>
                                <p class="text-muted small mb-0">Inscrições em eventos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('artesan.perfil') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-person-gear fs-1" style="color: #7a2f1f;"></i>
                            <div>
                                <h4 class="h6 fw-bold mb-1" style="color: #7a2f1f;">Meu Perfil</h4>
                                <p class="text-muted small mb-0">Editar perfil público</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Histórico de Vendas Próprias -->
    <div class="row g-4 mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color: #7a2f1f;"><i class="bi bi-receipt me-2"></i> Registro de Vendas dos Meus Produtos</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="px-4 py-3">Pedido</th>
                                <th class="px-4 py-3">Produto</th>
                                <th class="px-4 py-3">Qtd</th>
                                <th class="px-4 py-3">Preço Unitário</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Comprador</th>
                                <th class="px-4 py-3">Contato</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($minhasVendas as $itemVenda)
                            @if($itemVenda->venda)
                            <tr>
                                <td class="px-4 py-3">#{{ $itemVenda->venda->id_venda }}</td>
                                <td class="px-4 py-3 fw-bold" style="color: #8b5a3c;">{{ $itemVenda->produto->nome ?? 'Produto Excluído' }}</td>
                                <td class="px-4 py-3">{{ $itemVenda->quantidade }}</td>
                                <td class="px-4 py-3">R$ {{ number_format($itemVenda->preco_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 fw-bold" style="color: #c85a3a;">R$ {{ number_format($itemVenda->preco_unitario * $itemVenda->quantidade, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $itemVenda->venda->cliente->nome ?? 'Visitante' }}</td>
                                <td class="px-4 py-3">
                                    @if($itemVenda->venda->cliente && $itemVenda->venda->cliente->telefone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $itemVenda->venda->cliente->telefone) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0" style="font-size: 0.8rem;">
                                            <i class="bi bi-whatsapp"></i> WhatsApp
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($itemVenda->venda->mp_status === 'approved')
                                        <span class="badge bg-success">Pago</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Aguardando</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Você ainda não registrou nenhuma venda de seus produtos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($minhasVendas->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $minhasVendas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
