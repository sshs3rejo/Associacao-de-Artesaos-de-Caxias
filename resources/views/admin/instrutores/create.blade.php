@extends('layouts.main')

@section('titulo', 'Novo Instrutor')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Novo Instrutor</h1>

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

    <form action="{{ route('admin.instrutores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-input name="nome" label="Nome Completo" placeholder="Ex: João Silva" required />

        <x-input name="telefone" label="Telefone" placeholder="Ex: (11) 99999-9999" type="tel" />

        <x-input name="email" label="E-mail" placeholder="Ex: joao@exemplo.com" type="email" required />

        <x-input name="especialidade" label="Especialidade" placeholder="Ex: Pintura em Cerâmica" required />

        <x-textarea name="biografia" label="Biografia" placeholder="Breve descrição sobre o instrutor..." rows="5" />

        <x-input name="foto" label="Foto" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" help="JPEG, PNG, JPG ou WEBP. Máx. 2MB." />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.instrutores.index')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Instrutor</button>
        </div>
    </form>
</div>
@endsection
