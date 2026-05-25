@extends('layouts.main')

@section('titulo', 'Nova Compra de Matéria-Prima')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Nova Compra de Matéria-Prima</h1>

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

    <form action="{{ route('admin.compras-materia-prima.store') }}" method="POST">
        @csrf

        <x-select name="id_fornecedor" label="Fornecedor" :options="$fornecedores->pluck('nome', 'id_fornecedor')->toArray()" required />

        <x-select name="id_materia" label="Matéria-Prima" :options="$materiasPrimas->pluck('nome', 'id_materia')->toArray()" required />

        <x-input name="quantidade" label="Quantidade" type="number" step="any" placeholder="Ex: 10" required />

        <x-input name="preco_unitario" label="Preço Unitário (R$)" type="number" step="0.01" placeholder="Ex: 25,50" required />

        <x-input name="data_compra" label="Data da Compra" type="date" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.compras-materia-prima.index')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Compra</button>
        </div>
    </form>
</div>
@endsection
