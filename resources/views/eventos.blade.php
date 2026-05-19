@extends('layouts.main')
@section('titulo', 'Eventos - ' . config('association.name_short'))

@auth
    @if(auth()->user()->isAdmin())
        @section('content')
        <div class="container-fluid px-4 py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold mb-0" style="color: #7a2f1f;">Gerenciar Eventos</h1>
                <a href="{{ route('eventos.create') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
                    <i class="bi bi-plus-lg me-2"></i> Novo Evento
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($eventos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-calendar-event display-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fs-5">Nenhum evento cadastrado.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
                        <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                            <tr>
                                <th class="p-3">Capa</th>
                                <th class="p-3">Nome</th>
                                <th class="p-3">Proponente</th>
                                <th class="p-3">Tipo</th>
                                <th class="p-3">Data Início</th>
                                <th class="p-3">Local</th>
                                <th class="p-3">Vagas</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventos as $evento)
                                <tr>
                                    <td class="p-3">
                                        <img src="{{ $evento->imagem ? asset('storage/' . $evento->imagem) : config('association.placeholder') }}"
                                             alt="{{ $evento->nome }}" class="rounded shadow-sm" style="width: 55px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="fw-semibold p-3" style="color: #8b5a3c;">{{ $evento->nome }}</td>
                                    <td class="p-3">{{ $evento->artisan?->name ?? 'Admin' }}</td>
                                    <td class="p-3"><span class="badge bg-secondary">{{ ucfirst($evento->tipo_evento) }}</span></td>
                                    <td class="p-3 small">{{ $evento->data_inicio?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td class="p-3 small">{{ $evento->local }}</td>
                                    <td class="p-3">{{ $evento->vagas_disponiveis }}/{{ $evento->capacidade_maxima }}</td>
                                    <td class="p-3">
                                        @if($evento->is_approved)
                                            <span class="badge bg-success">Aprovado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <form action="{{ route('eventos.destroy', $evento->id_evento) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Remover evento {{ $evento->nome }} permanentemente?')">
                                                    <i class="bi bi-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $eventos->links() }}
                </div>
            @endif
        </div>
        @endsection

    @elseif(auth()->user()->isArtisan())
        @section('style')
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

            <div class="tab-content" id="eventosTabsContent">
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
                                            <div class="d-flex gap-2 mt-3 pt-2 border-top">
                                                <a href="{{ route('artesan.eventos.editar', $evento->id_evento) }}" class="btn btn-sm btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px;">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <form action="{{ route('artesan.eventos.deletar', $evento->id_evento) }}" method="POST" class="flex-fill m-0 p-0" onsubmit="return confirm('Tem certeza que deseja remover este evento?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px;">
                                                        <i class="bi bi-trash"></i> Remover
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

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
        @endsection

    @else
        @section('style')
        <style>
            .evento-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid rgba(122, 47, 31, 0.1);
                background-color: #ffffff;
            }
            .evento-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(122, 47, 31, 0.15) !important;
            }
            .btn-brown {
                background-color: #7a2f1f;
                color: #F9F7D3;
                border: none;
                transition: all 0.2s ease;
            }
            .btn-brown:hover {
                background-color: #8c3b2a;
                color: #ffffff;
            }
            .badge-status {
                background-color: #c85a3a;
                color: white;
            }
        </style>
        @endsection

        @section('content')
        <div class="container-fluid px-4 px-lg-5 py-5">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="fw-bold display-5 mb-3" style="color: #7a2f1f;">Nossos Eventos</h1>
                    <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
                        Fique por dentro das feiras, exposições e oficinas organizadas pela Associação dos Artesãos de Caxias.
                    </p>
                    <hr class="mx-auto mt-4" style="width: 80px; height: 3px; background-color: #c85a3a; opacity: 1; border: none; border-radius: 2px;">
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-xxl-10">
                    @if($eventos->isEmpty())
                        <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0" style="border: 1px dashed rgba(122, 47, 31, 0.2) !important;">
                            <i class="bi bi-calendar-x display-1 mb-3" style="color: #d1b8a4;"></i>
                            <h3 class="fw-semibold" style="color: #7a2f1f;">Nenhum evento no momento</h3>
                            <p class="text-muted fs-5">Fique de olho! Em breve teremos novidades.</p>
                        </div>
                    @else
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($eventos as $evento)
                                <div class="col">
                                    <div class="card h-100 rounded-4 overflow-hidden evento-card d-flex flex-column shadow-sm">
                                        <div class="position-relative" style="height: 200px; background-color: #f5f1ed;">
                                            @if($evento->imagem)
                                                <img src="{{ asset('storage/' . $evento->imagem) }}" alt="{{$evento->nome}}" class="w-100 h-100" style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-calendar-event display-3" style="color: #d1b8a4;"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge rounded-pill badge-status px-3 py-2 fw-semibold shadow-sm">
                                                    {{ $evento->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body p-4 d-flex flex-column text-center">
                                            <h4 class="card-title fw-bold mb-3 text-truncate" title="{{$evento->nome}}" style="color: #7a2f1f;">
                                                {{$evento->nome}}
                                            </h4>
                                            <div class="mt-auto d-flex flex-column align-items-center gap-2">
                                                <div class="fs-5 fw-bold" style="color: #c85a3a;">
                                                    @if($evento->isGratuito())
                                                        <i class="bi bi-tag-fill me-1"></i> Gratuito
                                                    @else
                                                        R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer bg-transparent border-0 p-3 pt-0 mt-auto">
                                            <a href="{{route('eventos.show', $evento->id_evento)}}" class="btn btn-brown w-100 rounded-pill py-2 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;">
                                                Mais Detalhes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endsection
    @endif
@else
    @section('style')
    <style>
        .evento-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(122, 47, 31, 0.1);
            background-color: #ffffff;
        }
        .evento-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(122, 47, 31, 0.15) !important;
        }
        .btn-brown {
            background-color: #7a2f1f;
            color: #F9F7D3;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-brown:hover {
            background-color: #8c3b2a;
            color: #ffffff;
        }
        .badge-status {
            background-color: #c85a3a;
            color: white;
        }
    </style>
    @endsection

    @section('content')
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="fw-bold display-5 mb-3" style="color: #7a2f1f;">Nossos Eventos</h1>
                <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
                    Fique por dentro das feiras, exposições e oficinas organizadas pela Associação dos Artesãos de Caxias.
                </p>
                <hr class="mx-auto mt-4" style="width: 80px; height: 3px; background-color: #c85a3a; opacity: 1; border: none; border-radius: 2px;">
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xxl-10">
                @if($eventos->isEmpty())
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0" style="border: 1px dashed rgba(122, 47, 31, 0.2) !important;">
                        <i class="bi bi-calendar-x display-1 mb-3" style="color: #d1b8a4;"></i>
                        <h3 class="fw-semibold" style="color: #7a2f1f;">Nenhum evento no momento</h3>
                        <p class="text-muted fs-5">Fique de olho! Em breve teremos novidades.</p>
                    </div>
                @else
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                        @foreach($eventos as $evento)
                            <div class="col">
                                <div class="card h-100 rounded-4 overflow-hidden evento-card d-flex flex-column shadow-sm">
                                    <div class="position-relative" style="height: 200px; background-color: #f5f1ed;">
                                        @if($evento->imagem)
                                            <img src="{{ asset('storage/' . $evento->imagem) }}" alt="{{$evento->nome}}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-calendar-event display-3" style="color: #d1b8a4;"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <span class="badge rounded-pill badge-status px-3 py-2 fw-semibold shadow-sm">
                                                {{ $evento->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body p-4 d-flex flex-column text-center">
                                        <h4 class="card-title fw-bold mb-3 text-truncate" title="{{$evento->nome}}" style="color: #7a2f1f;">
                                            {{$evento->nome}}
                                        </h4>
                                        <div class="mt-auto d-flex flex-column align-items-center gap-2">
                                            <div class="fs-5 fw-bold" style="color: #c85a3a;">
                                                @if($evento->isGratuito())
                                                    <i class="bi bi-tag-fill me-1"></i> Gratuito
                                                @else
                                                    R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-0 p-3 pt-0 mt-auto">
                                        <a href="{{route('eventos.show', $evento->id_evento)}}" class="btn btn-brown w-100 rounded-pill py-2 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;">
                                            Mais Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endsection
@endauth