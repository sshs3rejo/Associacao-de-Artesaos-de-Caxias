@extends('layouts.main')
@section('titulo', 'Meus Produtos - Artesão')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0" style="color: #7a2f1f;">Meus Produtos</h1>
        <a href="{{ route('artesan.produtos.criar') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
            <i class="bi bi-plus-lg me-2"></i> Propor Novo Produto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($produtos->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Você ainda não cadastrou ou propôs produtos.</p>
            <p class="text-muted">Clique no botão acima para propor o seu primeiro produto de artesanato!</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($produtos as $produto)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="overflow-hidden position-relative" style="height: 180px;">
                            <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : config('association.placeholder') }}"
                                 class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover;">
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h5 class="fw-bold mb-0 text-truncate" style="color: #7a2f1f;" title="{{ $produto->nome }}">{{ $produto->nome }}</h5>
                                @if($produto->is_approved)
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Ativo</span>
                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Pendente</span>
                                @endif
                            </div>
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
