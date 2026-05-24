@extends('layouts.main')

@section('titulo', 'Novo Fornecedor')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Novo Fornecedor</h1>

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

    <form action="{{ route('admin.fornecedores.store') }}" method="POST">
        @csrf

        <x-input name="nome" label="Nome" placeholder="Ex: Madeireira Silva" required />

        <x-input name="contato" label="Contato" placeholder="Ex: João Silva" />

        <x-input name="email" label="Email" type="email" placeholder="Ex: contato@fornecedor.com" />

        <x-input name="telefone" label="Telefone" placeholder="Ex: (11) 99999-8888" />

        <x-textarea name="endereco" label="Endereço" placeholder="Ex: Rua das Flores, 123, Centro" rows="3" />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.fornecedores.index')" label="Cancelar" />
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">Salvar Fornecedor</button>
        </div>
    </form>
</div>
@endsection
