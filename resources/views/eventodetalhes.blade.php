@extends('layouts.main')
@section('titulo', 'Detalhes do evento')

@section('content')
<div class="max-w-7xl mx-auto px-4 mt-4 mb-5">
    <a href="{{ route('evento') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
        <i class="fas fa-arrow-left text-xs"></i> Voltar para Eventos
    </a>
    <div class="shadow border p-4 bg-white rounded">
    <div class="flex flex-wrap justify-center">
        <div class="w-full md:w-5/12 text-center mb-4">
            <x-image :src="$evento->imagem" alt="$evento->nome" class="w-full h-auto border rounded shadow-sm" style="object-fit: cover; max-height: 400px;" />
        </div>

        <div class="w-full md:w-5/12">
            <h2 class="font-bold mb-2 text-center text-brand">{{ $evento->nome }}</h2>
            <div>
                <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-green-500 text-white spanhover mb-3">{{$evento->tipo_evento}}</span>
                <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-green-500 text-white spanhover mb-3">{{$evento->local}}</span>
                <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-green-500 text-white spanhover mb-3">{{$evento->status}}</span>
            </div>
            <div>
                <p>
                    Início: {{ \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y \à\s H:i') }}
                    <br>
                    Fim: {{ \Carbon\Carbon::parse($evento->data_fim)->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
            <div>
                <p>Vagas disponíveis: <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-green-500 text-white spanhover">{{$evento->vagas_disponiveis}}</span></p>
            </div>
            <div>
                <p>Instrutor: {{ $evento->instrutor?->nome ?? 'Não informado' }}</p>
            </div>
            <div>
                @if($evento->isGratuito())
                    <p class="text-xl font-bold text-green-600 textEvento">Gratuito</p>
                @else
                    <p class="text-xl font-bold text-price textEvento">R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}</p>
                @endif
            </div>

            <p class="texto-descricao">{{ $evento->descricao }}</p>

            @auth
                @if($jaInscrito ?? false)
                    <form action="{{ route('eventos.cancelar-inscricao', $evento->id_evento) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block px-4 py-2 rounded-lg font-semibold w-full py-2 mt-2 font-bold border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition">
                            <i class="fas fa-times-circle me-1"></i> Cancelar Inscrição
                        </button>
                    </form>
                @elseif($evento->isLotado())
                    <button class="inline-block px-4 py-2 rounded-lg font-semibold w-full py-2 mt-2 font-bold bg-gray-400 text-white cursor-not-allowed" disabled>
                        <i class="fas fa-exclamation-circle me-1"></i> Evento Lotado
                    </button>
                @else
                    <form action="{{ route('eventos.inscrever', $evento->id_evento) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-block px-4 py-2 rounded-lg font-semibold w-full py-2 mt-2 font-bold bg-green-500 text-white hover:bg-green-600 transition">
                            <i class="fas fa-check-circle me-1"></i> Inscrever-se
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login.form') }}" class="inline-block px-4 py-2 rounded-lg font-semibold w-full py-2 mt-2 font-bold bg-green-500 text-white hover:bg-green-600 transition">
                    <i class="fas fa-sign-in-alt me-1"></i> Faça login para se inscrever
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
