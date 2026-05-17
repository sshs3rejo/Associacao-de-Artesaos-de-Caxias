@extends('layouts.main')

@section('titulo', 'Painel Administrativo')

@section('style')
<style>
    /* ==========================================
       ESTILOS COMPLETOS DO DASHBOARD
       FORÇANDO TODOS OS ESTILOS INLINE
       ========================================== */

    /* BOTÕES EDITAR E EXCLUIR - COM BOXES */
    .btn-action-edit,
    .btn-action-delete,
    a.btn-action-edit,
    button.btn-action-delete {
        display: inline-block !important;
        padding: 6px 16px !important;
        margin: 0 4px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        text-align: center !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        border: 2px solid !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    /* BOTÃO EDITAR - LARANJA */
    .btn-action-edit,
    a.btn-action-edit {
        background-color: #fff !important;
        border-color: #f0ad4e !important;
        color: #f0ad4e !important;
    }

    .btn-action-edit:hover,
    a.btn-action-edit:hover {
        background-color: #f0ad4e !important;
        border-color: #f0ad4e !important;
        color: #fff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(240,173,78,0.3) !important;
    }

    /* BOTÃO EXCLUIR - VERMELHO */
    .btn-action-delete,
    button.btn-action-delete {
        background-color: #fff !important;
        border-color: #d9534f !important;
        color: #d9534f !important;
    }

    .btn-action-delete:hover,
    button.btn-action-delete:hover {
        background-color: #d9534f !important;
        border-color: #d9534f !important;
        color: #fff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(217,83,79,0.3) !important;
    }

    /* CONTAINER DOS BOTÕES */
    .action-buttons {
        display: flex !important;
        gap: 8px !important;
        align-items: center !important;
        justify-content: flex-end !important;
    }

    /* CARDS E TABELAS */
    .data-table {
        background: #fff !important;
        border-radius: 12px !important;
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .dashboard-card {
        background: #fff !important;
        border-radius: 12px !important;
        padding: 1.5rem !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }

    .dashboard-card h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #444;
        margin-bottom: 0.5rem;
    }

    .dashboard-card strong {
        display: block;
        font-size: 1.8rem;
        color: #5C3A2C;
        font-weight: 700;
    }

    .dashboard-card a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .dashboard-card a:hover strong {
        color: #8C5E47;
    }

    .dashboard-card a:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    /* BOTÕES PRINCIPAIS DO DASHBOARD */
    .btn-dashboard-primary {
        background-color: #5C3A2C !important;
        border: 2px solid #5C3A2C !important;
        color: #fff !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.2s !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    .btn-dashboard-primary:hover {
        background-color: #8C5E47 !important;
        border-color: #8C5E47 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }

    .btn-dashboard-outline {
        background-color: #fff !important;
        border: 2px solid #8C5E47 !important;
        color: #8C5E47 !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.2s !important;
    }

    .btn-dashboard-outline:hover {
        background-color: #8C5E47 !important;
        border-color: #8C5E47 !important;
        color: #fff !important;
        transform: translateY(-1px) !important;
    }

    /* RESPONSIVO MOBILE */
    @media (max-width: 767px) {
        .action-buttons {
            flex-direction: column !important;
            gap: 6px !important;
        }

        .btn-action-edit,
        .btn-action-delete {
            width: 100% !important;
            margin: 0 !important;
        }

        .data-table {
            padding: 1rem !important;
        }
    }
</style>
@endsection




@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Painel Administrativo</h1>
            <p class="text-muted mb-0">Visão geral das principais métricas do sistema.</p>
        </div>
    </div>

    {{-- Mensagens de feedback --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ops!</strong> Corrija os campos destacados abaixo.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    {{-- Gerenciamento rápido removido daqui para páginas próprias --}}

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
            <h3>Categorias de produtos</h3>
            <strong>{{ $stats['categorias'] }}</strong>
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
    </section>

    {{-- Histórico de Vendas --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="data-table p-0">
                <div class="p-4 border-bottom">
                    <h2 class="h5 mb-0 fw-bold" style="color: #7a2f1f;">Histórico de Vendas</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Data</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendas as $venda)
                            <tr>
                                <td class="px-4 py-3">#{{ $venda->id_venda }}</td>
                                <td class="px-4 py-3">{{ $venda->cliente->nome ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if($venda->mp_status === 'approved')
                                        <span class="badge bg-success">Pago</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        @if($venda->mp_status !== 'approved')
                                        <form action="{{ route('admin.vendas.aprovar', $venda->id_venda) }}" method="POST" class="d-inline m-0 p-0">
                                            @csrf
                                            <button type="submit" class="btn btn-success text-white fw-bold px-3 py-1 rounded shadow-sm d-flex align-items-center gap-1" style="font-size: 0.75rem; border: none; border-radius: 6px !important;">
                                                <i class="fas fa-check"></i> Aprovar Pix
                                            </button>
                                        </form>
                                        @endif
                                        <button class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center" title="Itens do Pedido: @foreach($venda->itens as $item) {{ $item->produto->nome }} x{{ $item->quantidade }}; @endforeach" style="width: 28px; height: 28px; border-radius: 6px;">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Nenhuma venda encontrada.</td>
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
</div>
@endsection
