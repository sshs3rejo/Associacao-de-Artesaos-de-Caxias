@extends('layouts.main')
@section('titulo', 'Meus Eventos - Artesão')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Meus Eventos</h1>

    @if($inscricoes->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-calendar-x display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Você não está inscrito em nenhum evento.</p>
            <a href="{{ route('evento') }}" class="btn fw-bold px-4" style="background-color: #7a2f1f; color: #F9F7D3;">
                Ver Eventos Disponíveis
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($inscricoes as $inscricao)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2" style="color: #7a2f1f;">{{ $inscricao->evento->nome }}</h5>
                            <div class="small text-muted mb-3">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $inscricao->evento->data_inicio?->format('d/m/Y H:i') ?? 'Data a definir' }}
                            </div>
                            <span class="badge {{ $inscricao->isPago() ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $inscricao->status_pagamento }}
                            </span>
                            <span class="badge bg-secondary ms-1">{{ $inscricao->evento->status }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
