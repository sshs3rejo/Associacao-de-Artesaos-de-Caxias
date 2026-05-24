@extends('layouts.main')

@section('titulo', 'Editar Fornecedor')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Fornecedor</h1>

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

    <form action="{{ route('admin.fornecedores.update', $fornecedore->id_fornecedor) }}" method="POST">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome" value="{{ $fornecedore->nome }}" required />

        <x-input name="contato" label="Contato" value="{{ $fornecedore->contato }}" />

        <x-input name="email" label="Email" type="email" value="{{ $fornecedore->email }}" />

        <x-input name="telefone" label="Telefone" value="{{ $fornecedore->telefone }}" />

        <x-textarea name="endereco" label="Endereço" value="{{ $fornecedore->endereco }}" rows="3" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.fornecedores.index')" label="Voltar" />
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">Atualizar Fornecedor</button>
        </div>
    </form>
</div>
@endsection
