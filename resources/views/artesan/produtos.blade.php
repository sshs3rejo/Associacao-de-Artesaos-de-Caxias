@extends('layouts.main')
@section('titulo', 'Meus Produtos - Artesão')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Meus Produtos</h1>

    @if($produtos->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Você ainda não cadastrou produtos.</p>
            <p class="text-muted">Entre em contato com o administrador para adicionar seus produtos.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($produtos as $produto)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="overflow-hidden" style="height: 180px;">
                            <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : config('association.placeholder') }}"
                                 class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover;">
                        </div>
                        <div class="card-body p-3">
                            <h5 class="fw-bold mb-1" style="color: #7a2f1f;">{{ $produto->nome }}</h5>
                            <p class="text-muted small mb-2">{{ $produto->categoria?->nome_categoria }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="color: #c85a3a;">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                <span class="small text-muted">Estoque: {{ $produto->estoque?->quantidade ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
