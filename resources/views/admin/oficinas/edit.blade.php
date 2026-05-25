@extends('layouts.main')

@section('titulo', 'Editar Oficina')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Oficina</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative" id="err-box">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-700 hover:text-red-900 cursor-pointer border-0 bg-transparent">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.oficinas.update', $oficina->id) }}" method="POST">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome" value="{{ $oficina->nome }}" required />

        <x-textarea name="descricao" label="Descrição" rows="4">{{ $oficina->descricao }}</x-textarea>

        <x-input name="carga_horaria" label="Carga Horária" value="{{ $oficina->carga_horaria }}" type="number" step="0.1" />

        <x-select name="id_instrutor" label="Instrutor" :options="$instrutores->pluck('nome', 'id_instrutor')->toArray()" value="{{ $oficina->id_instrutor }}" placeholder="Selecione um instrutor..." required />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input name="data_inicio" label="Data de Início" type="date" value="{{ $oficina->data_inicio ? \Carbon\Carbon::parse($oficina->data_inicio)->format('Y-m-d') : '' }}" required />
            <x-input name="data_fim" label="Data de Término" type="date" value="{{ $oficina->data_fim ? \Carbon\Carbon::parse($oficina->data_fim)->format('Y-m-d') : '' }}" />
        </div>

        <x-input name="horario" label="Horário" value="{{ $oficina->horario }}" placeholder="Ex: Segundas e Quartas, 14h às 17h" />

        <x-input name="local" label="Local" value="{{ $oficina->local }}" placeholder="Ex: Sala 3, Centro Cultural" />

        <x-input name="vagas" label="Vagas" value="{{ $oficina->vagas }}" type="number" min="0" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.oficinas.index')" label="Voltar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Atualizar Oficina</button>
        </div>
    </form>
</div>
@endsection
