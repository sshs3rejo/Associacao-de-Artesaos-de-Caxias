@extends('layouts.main')

@section('titulo', 'Editar Evento - Artesão')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Evento / Oficina</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative" id="err-box">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-700 hover:text-red-900 cursor-pointer border-0 bg-transparent">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os erros abaixo:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artesan.eventos.atualizar', $evento->id_evento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome do Evento / Oficina" placeholder="Ex: Oficina Prática de Cerâmica Figurativa" value="{{ $evento->nome }}" required />

        <x-textarea name="descricao" label="Descrição Completa e Cronograma" placeholder="Detalhe o que os participantes aprenderão, pré-requisitos e materiais incluídos" rows="4" value="{{ $evento->descricao }}" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-select name="tipo_evento" label="Tipo de Evento" :options="['workshop' => 'Oficina / Workshop', 'feira' => 'Feira de Artesanato', 'exposicao' => 'Exposição', 'palestra' => 'Palestra / Roda de Conversa', 'lancamento' => 'Lançamento', 'outro' => 'Outro']" value="{{ $evento->tipo_evento }}" placeholder="Selecione..." required />
            </div>
            <div>
                <x-input name="capacidade_maxima" label="Capacidade Máxima" type="number" placeholder="Ex: 15" value="{{ $evento->capacidade_maxima }}" required />
            </div>
            <div>
                <x-input name="valor_inscricao" label="Valor da Inscrição (R$)" type="number" step="0.01" placeholder="0,00 (Deixe 0 se gratuito)" value="{{ $evento->valor_inscricao }}" required />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input name="data_inicio" label="Data e Hora de Início" type="datetime-local" value="{{ $evento->data_inicio?->format('Y-m-d\TH:i') }}" required />
            </div>
            <div>
                <x-input name="data_fim" label="Data e Hora do Fim" type="datetime-local" value="{{ $evento->data_fim?->format('Y-m-d\TH:i') }}" required />
            </div>
            <div>
                <x-input name="local" label="Local do Evento" placeholder="Ex: Sede da Associação, Sala A" value="{{ $evento->local }}" required />
            </div>
        </div>

        <div class="mb-6">
            <label for="imagem" class="block font-bold mb-1 text-brand">Imagem de Capa (Banner/Folder)</label>
            @if($evento->imagem)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $evento->imagem) }}" alt="Imagem atual" class="rounded shadow-sm" style="max-height: 100px; object-fit: cover;" loading="lazy">
                </div>
            @endif
            <input class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror" type="file" id="imagem" name="imagem" accept="image/*">
            @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-between items-center">
            <x-back-button :route="route('evento')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Alterações</button>
        </div>
    </form>
</div>
@endsection
