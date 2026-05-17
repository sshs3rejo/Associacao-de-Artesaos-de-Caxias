@extends('layouts.main')
@section('titulo', $user->name . ' - Artesão')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <div class="mb-4">
                @if($perfil->profile_photo)
                    <img src="{{ asset('storage/' . $perfil->profile_photo) }}" alt="{{ $user->name }}"
                         class="rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                         style="width: 150px; height: 150px; background-color: #F9F7D3;">
                        <i class="bi bi-person display-4" style="color: #7a2f1f;"></i>
                    </div>
                @endif
            </div>
            <h1 class="fw-bold mb-2" style="color: #7a2f1f;">{{ $user->name }}</h1>
            @if($perfil->specialty)
                <p class="fs-5 text-muted mb-3"><i class="bi bi-star me-2"></i>{{ $perfil->specialty }}</p>
            @endif
            @if($perfil->bio)
                <p class="lead" style="max-width: 600px; margin: 0 auto;">{{ $perfil->bio }}</p>
            @endif
            <div class="d-flex justify-content-center gap-3 mt-4">
                @if($perfil->instagram)
                    <a href="https://instagram.com/{{ ltrim($perfil->instagram, '@') }}" target="_blank" class="btn btn-outline-dark rounded-pill">
                        <i class="fab fa-instagram me-1"></i> Instagram
                    </a>
                @endif
                @if($perfil->facebook)
                    <a href="{{ $perfil->facebook }}" target="_blank" class="btn btn-outline-dark rounded-pill">
                        <i class="fab fa-facebook me-1"></i> Facebook
                    </a>
                @endif
                @if($perfil->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $perfil->whatsapp) }}" target="_blank" class="btn btn-success rounded-pill">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($produtos->isNotEmpty())
        <h2 class="fw-bold text-center mb-4" style="color: #7a2f1f;">Produtos</h2>
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
                            <span class="fw-bold" style="color: #c85a3a;">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
