@extends('layouts.main')

@section('titulo', 'Editar Evento')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Evento</h1>

    {{-- Exibe erros de validação --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative" id="err-edit-evento">
            <button type="button" class="absolute top-2 right-2 text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Verifique os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário --}}
    <form action="{{ route('eventos.update', $evento->id_evento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome do Evento" value="{{ $evento->nome }}" required />

        <x-textarea name="descricao" label="Descrição" rows="4" required value="{{ $evento->descricao }}" />

        {{-- Linha 1: Tipo / Data Inicio / Data Fim --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-select name="tipo_evento" label="Tipo do Evento" :options="['feira' => 'Feira', 'exposicao' => 'Exposição', 'workshop' => 'Workshop', 'lancamento' => 'Lançamento', 'palestra' => 'Palestra', 'outro' => 'Outro']" value="{{ $evento->tipo_evento }}" placeholder="Selecione..." required />
            </div>
            <div>
                <x-input name="data_inicio" label="Data e Hora de Início" type="datetime-local" value="{{ optional($evento->data_inicio)->format('Y-m-d\TH:i') }}" required />
            </div>
            <div>
                <x-input name="data_fim" label="Data e Hora de Fim" type="datetime-local" value="{{ optional($evento->data_fim)->format('Y-m-d\TH:i') }}" required />
            </div>
        </div>

        {{-- Linha 2: Local / Capacidade / Valor / Status --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4">
                <x-input name="local" label="Local" value="{{ $evento->local }}" required />
            </div>
            <div class="md:col-span-3">
                <x-input name="capacidade_maxima" label="Capacidade Máx." type="number" value="{{ $evento->capacidade_maxima }}" required />
            </div>
            <div class="md:col-span-2">
                <x-input name="valor_inscricao" label="Valor (R$)" type="number" step="0.01" value="{{ $evento->valor_inscricao }}" required />
            </div>
            <div class="md:col-span-3">
                <x-select name="status" label="Status" :options="['planejado' => 'Planejado', 'confirmado' => 'Confirmado', 'em_andamento' => 'Em Andamento', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado']" value="{{ $evento->status }}" required />
            </div>
        </div>

        {{-- Instrutor --}}
        <div class="mb-4">
            <label for="nome_instrutor" class="block font-bold mb-1 text-brand">Instrutor Responsável</label>
            <input type="text" list="instrutores_list" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none" id="nome_instrutor" name="nome_instrutor"
                   placeholder="Digite o nome ou selecione..." value="{{ old('nome_instrutor', optional($evento->instrutor)->nome) }}">
            @error('nome_instrutor') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            <datalist id="instrutores_list">
                @foreach ($instrutores as $instrutor)
                    <option value="{{ $instrutor->nome }}">
                @endforeach
            </datalist>
            <small class="text-sm text-gray-500 mt-1 block">Se o instrutor não existir, ele será criado automaticamente.</small>
        </div>

        {{-- Imagem --}}
        <div class="mb-6">
            <label for="imagem" class="block font-bold mb-1 text-brand">Imagem do Evento</label>
            <input class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror" type="file" id="imagem" name="imagem" accept="image/*">
            @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror

            @if ($evento->imagem)
                <small class="text-gray-500 block mt-2">Imagem atual:</small>
                <img src="{{ asset('storage/' . $evento->imagem) }}" alt="Imagem atual do evento" class="img-preview" loading="lazy">
            @endif
        </div>

        {{-- Botões --}}
        <div class="flex justify-between items-center">
            <x-back-button :route="route('evento')" label="Voltar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">
                <x-icon name="check-circle" class="w-4 h-4" /> Atualizar Evento
            </button>
        </div>
    </form>
</div>
@endsection
