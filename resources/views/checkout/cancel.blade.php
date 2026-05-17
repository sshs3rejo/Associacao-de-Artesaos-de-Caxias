@extends('layouts.main')
@section('titulo', 'Pagamento Pendente')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="bi bi-clock-history text-warning" style="font-size: 4rem;"></i>
            </div>
            <h1 class="fw-bold mb-3" style="color: #5C3A2C;">Pagamento não finalizado</h1>
            <p class="text-muted mb-4">O pagamento do pedido <strong>#{{ $venda->id_venda }}</strong> não foi concluído ou está pendente.</p>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-start p-4">
                    <h5 class="fw-bold mb-3" style="color: #5C3A2C;">Detalhes</h5>
                    <p class="mb-1"><strong>Cliente:</strong> {{ $venda->cliente->nome }}</p>
                    <p class="mb-1"><strong>Valor:</strong> R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</p>
                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning text-dark">{{ $venda->mp_status }}</span></p>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('produtos') }}" class="btn btn-lg px-4 fw-bold" style="border: 2px solid #7a2f1f; color: #7a2f1f; border-radius: 50rem;">
                    <i class="bi bi-arrow-left me-2"></i> Voltar às Compras
                </a>
                <a href="{{ route('home') }}" class="btn btn-lg px-4 text-white fw-bold" style="background-color: #7a2f1f; border-radius: 50rem;">
                    Página Inicial
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
