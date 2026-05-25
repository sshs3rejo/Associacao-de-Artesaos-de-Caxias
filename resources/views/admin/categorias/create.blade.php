@extends('layouts.main')

@section('titulo', 'Nova Categoria')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Nova Categoria</h1>

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

    <form action="{{ route('admin.categorias.store') }}" method="POST">
        @csrf

        <x-input name="nome_categoria" label="Nome da Categoria" placeholder="Ex: Artesanato em Madeira" required />

        <x-select name="parent_id" label="Categoria Pai" :options="$parents->pluck('nome_categoria', 'id_categoria')->toArray()" placeholder="Nenhuma (categoria raiz)" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.categorias.index')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Categoria</button>
        </div>
    </form>
</div>
@endsection
