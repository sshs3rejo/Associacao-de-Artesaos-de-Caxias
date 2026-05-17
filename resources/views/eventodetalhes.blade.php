@extends('layouts.main')
@section('titulo', 'Detalhes do evento')

@section('style')
<link rel="stylesheet" href="{{asset('css/style-eventodetalhes.css')}}">
@endsection

@section('content')
<div class="container mt-4 mb-5 shadow border p-4 bg-white rounded">
    <div class="row justify-content-center">
        <!-- Imagem -->
        <div class="col-12 col-md-5 text-center mb-4">
            <img class="img-fluid border rounded shadow-sm produto-img" src="{{ $evento->imagem ? asset('storage/' . $evento->imagem) : config('association.placeholder') }}" alt="{{ $evento->nome }}">
        </div>

        <!-- Informações -->
        <div class="col-12 col-md-5">
            <h2 class="fw-bold mb-2 text-center">{{ $evento->nome }}</h2>
            <div>
                <span class="badge bg-success spanhover mb-3 px-3 py-2">{{$evento->tipo_evento}}</span>
                <span class="badge bg-success spanhover mb-3 px-3 py-2">{{$evento->local}}</span>
                <span class="badge bg-success spanhover mb-3 px-3 py-2">{{$evento->status}}</span>
            </div>
            <div class="">
                <p>
                    Início: {{ \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y \à\s H:i') }} 
                    <br>
                    Fim: {{ \Carbon\Carbon::parse($evento->data_fim)->format('d/m/Y \à\s H:i') }}
                </p>

            </div>
            <div>
                <p>Vagas disponíveis: <span class="badge bg-success spanhover">{{$evento->vagas_disponiveis}}</span></p> 
            </div>
            <div>
                <p>Instrutor: {{ $evento->instrutor?->nome ?? 'Não informado' }}</p> 
            </div>
            <div >
                @if($evento->isGratuito())
                    <p class="h4 text-success fw-bold textEvento">Gratuito</p>
                @else
                    <p  class="h4 textEvent fw-bold textEvento">R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}</p>
                @endif
            </div>

           

            <p class="texto-descricao">{{ $evento->descricao }}</p>

            @auth
                @if($jaInscrito ?? false)
                    <form action="{{ route('eventos.cancelar-inscricao', $evento->id_evento) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 mt-2 fw-bold">
                            <i class="bi bi-x-circle me-1"></i> Cancelar Inscrição
                        </button>
                    </form>
                @elseif($evento->isLotado())
                    <button class="btn btn-secondary w-100 py-2 mt-2 fw-bold" disabled>
                        <i class="bi bi-exclamation-circle me-1"></i> Evento Lotado
                    </button>
                @else
                    <form action="{{ route('eventos.inscrever', $evento->id_evento) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 mt-2 fw-bold">
                            <i class="bi bi-check-circle me-1"></i> Inscrever-se
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login.form') }}" class="btn btn-success w-100 py-2 mt-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Faça login para se inscrever
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection