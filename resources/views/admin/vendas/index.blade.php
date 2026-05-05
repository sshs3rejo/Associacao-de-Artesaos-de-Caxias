@extends('layouts.main')

@section('titulo', 'Histórico de Vendas - Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" style="color: #7a2f1f;">Histórico de Vendas</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">Voltar ao Painel</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendas as $venda)
                        <tr>
                            <td class="px-4 py-3">{{ $venda->id_venda }}</td>
                            <td class="px-4 py-3">{{ $venda->cliente->nome ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <button class="btn btn-sm btn-info text-white" title="Ver Itens">
                                    <i class="fas fa-eye"></i>
                                </button>
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
    </div>

    <div class="mt-4">
        {{ $vendas->links() }}
    </div>
</div>
@endsection
