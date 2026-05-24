@extends('layouts.main')

@section('titulo', 'Editar Compra de Matéria-Prima')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Compra de Matéria-Prima</h1>

    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
            <button type="button" class="absolute top-2 right-2 text-red-700 hover:text-red-900" @click="show = false">
                <i class="fas fa-times"></i>
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.compras-materia-prima.update', $compraMateriaPrima->id_compra) }}" method="POST">
        @csrf
        @method('PUT')

        <x-select name="id_fornecedor" label="Fornecedor" :options="$fornecedores->pluck('nome', 'id_fornecedor')->toArray()" value="{{ $compraMateriaPrima->id_fornecedor }}" required />

        <x-select name="id_materia" label="Matéria-Prima" :options="$materiasPrimas->pluck('nome', 'id_materia')->toArray()" value="{{ $compraMateriaPrima->id_materia }}" required />

        <x-input name="quantidade" label="Quantidade" type="number" step="any" value="{{ $compraMateriaPrima->quantidade }}" required />

        <x-input name="preco_unitario" label="Preço Unitário (R$)" type="number" step="0.01" value="{{ $compraMateriaPrima->preco_unitario }}" required />

        <x-input name="data_compra" label="Data da Compra" type="date" value="{{ $compraMateriaPrima->data_compra ? \Carbon\Carbon::parse($compraMateriaPrima->data_compra)->format('Y-m-d') : '' }}" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.compras-materia-prima.index')" label="Voltar" />
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">Atualizar Compra</button>
        </div>
    </form>
</div>
@endsection
