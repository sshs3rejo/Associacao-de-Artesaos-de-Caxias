@extends('layouts.main')
@section('titulo', 'Meus Eventos - Artesão')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0" style="color: #7a2f1f;">Meus Eventos</h1>
        <a href="{{ route('artesan.eventos.criar') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
            <i class="bi bi-plus-lg me-2"></i> Propor Novo Evento
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Menu de Abas --}}
    <ul class="nav nav-pills mb-4 border-bottom pb-2 gap-2" id="eventosTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-bold" id="propostas-tab" data-bs-toggle="tab" data-bs-target="#propostas" type="button" role="tab" aria-controls="propostas" aria-selected="true" style="color: #7a2f1f; border: 1px solid #7a2f1f;">
                Minhas Propostas de Eventos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold" id="inscricoes-tab" data-bs-toggle="tab" data-bs-target="#inscricoes" type="button" role="tab" aria-controls="inscricoes" aria-selected="false" style="color: #7a2f1f; border: 1px solid #7a2f1f;">
                Eventos que Vou Participar
            </button>
        </li>
    </ul>

    {{-- Conteúdo das Abas --}}
    <div class="tab-content" id="eventosTabsContent">
        
        {{-- TAB 1: PROPOSTAS DE EVENTOS --}}
        <div class="tab-pane fade show active" id="propostas" role="tabpanel" aria-labelledby="propostas-tab">
            @if($eventosPropostos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-calendar-event display-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fs-5">Você ainda não propôs nenhum evento ou oficina.</p>
                    <p class="text-muted">Clique no botão "+ Propor Novo Evento" para começar!</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($eventosPropostos as $evento)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                <div class="overflow-hidden position-relative" style="height: 140px; background-color: #eee;">
                                    <img src="{{ $evento->imagem ? asset('storage/' . $evento->imagem) : config('association.placeholder') }}"
                                         class="w-100 h-100" alt="{{ $evento->nome }}" style="object-fit: cover;">
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <h5 class="fw-bold mb-0 text-truncate" style="color: #7a2f1f;" title="{{ $evento->nome }}">{{ $evento->nome }}</h5>
                                        @if($evento->is_approved)
                                            <span class="badge bg-success" style="font-size: 0.7rem;">Ativo</span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Pendente</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $evento->data_inicio?->format('d/m/Y H:i') ?? 'Data a definir' }}
                                    </div>
                                    <div class="small text-muted mb-3">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $evento->local }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <span class="fw-bold" style="color: #c85a3a;">
                                            {{ $evento->isGratuito() ? 'Gratuito' : 'R$ ' . number_format($evento->valor_inscricao, 2, ',', '.') }}
                                        </span>
                                        <span class="small text-muted">Vagas: {{ $evento->vagas_disponiveis }}/{{ $evento->capacidade_maxima }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TAB 2: INSCRIÇÕES EM EVENTOS --}}
        <div class="tab-pane fade" id="inscricoes" role="tabpanel" aria-labelledby="inscricoes-tab">
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

    </div>
</div>

<style>
    .nav-pills .nav-link.active {
        background-color: #7a2f1f !important;
        color: #fff !important;
    }
    .nav-pills .nav-link:not(.active):hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
