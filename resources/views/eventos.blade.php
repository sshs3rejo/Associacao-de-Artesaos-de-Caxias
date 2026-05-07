@extends('layouts.main')
@section('titulo', 'Eventos da Associação')

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
    
    <!-- Cabeçalho da Página -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5 mb-3" style="color: #7a2f1f;">Nossos Eventos</h1>
            <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
                Fique por dentro das feiras, exposições e oficinas organizadas pela Associação dos Artesãos de Caxias.
            </p>
            <hr class="mx-auto mt-4" style="width: 80px; height: 3px; background-color: #c85a3a; opacity: 1; border: none; border-radius: 2px;">
        </div>
    </div>

    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="row mb-4 justify-content-center">
        <div class="col-12 col-xxl-10 d-flex justify-content-end">
            <a href="{{ route('eventos.create') }}" class="btn text-white fw-bold shadow-sm px-4" style="background-color: #7a2f1f; border-radius: 10px;">
                <i class="fa fa-plus-circle me-1"></i> Adicionar Novo Evento
            </a>
        </div>
    </div>
    @endif

    <!-- Grid de Eventos -->
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
                                
                                <!-- Imagem do Evento -->
                                <div class="position-relative" style="height: 200px; background-color: #f5f1ed;">
                                    @if($evento->imagem)
                                        <img src="{{$evento->imagem}}" alt="{{$evento->nome}}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <!-- Placeholder se não houver imagem -->
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-event display-3" style="color: #d1b8a4;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge de Status -->
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge rounded-pill badge-status px-3 py-2 fw-semibold shadow-sm">
                                            {{ $evento->status }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Corpo do Card -->
                                <div class="card-body p-4 d-flex flex-column text-center">
                                    <h4 class="card-title fw-bold mb-3 text-truncate" title="{{$evento->nome}}" style="color: #7a2f1f;">
                                        {{$evento->nome}}
                                    </h4>
                                    
                                    <div class="mt-auto d-flex flex-column align-items-center gap-2">
                                        <!-- Valor / Gratuito -->
                                        <div class="fs-5 fw-bold" style="color: #c85a3a;">
                                            @if($evento->isGratuito())
                                                <i class="bi bi-tag-fill me-1"></i> Gratuito
                                            @else
                                                R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Rodapé do Card (Botão) -->
                                <div class="card-footer bg-transparent border-0 p-3 pt-0 mt-auto">
                                    <a href="{{route('eventos.show', $evento->id_evento)}}" class="btn btn-brown w-100 rounded-pill py-2 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;">
                                        Mais Detalhes
                                    </a>
                                    
                                    @if(auth()->check() && auth()->user()->isAdmin())
                                    <div class="d-flex justify-content-between mt-2 gap-2">
                                        <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="btn btn-sm btn-outline-warning fw-bold flex-grow-1" style="font-size: 0.8rem; color: #d39e00; border-color: #d39e00;">
                                            <i class="fa fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('eventos.destroy', $evento->id_evento) }}" method="POST" class="d-inline flex-grow-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold w-100" style="font-size: 0.8rem;" onclick="confirmarExclusao(this)">
                                                <i class="fa fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                    @endif
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