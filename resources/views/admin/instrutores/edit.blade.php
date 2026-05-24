@extends('layouts.main')

@section('titulo', 'Editar Instrutor')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Instrutor</h1>

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

    <form action="{{ route('admin.instrutores.update', $instrutor->id_instrutor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome Completo" value="{{ $instrutor->nome }}" required />

        <x-input name="telefone" label="Telefone" value="{{ $instrutor->telefone }}" type="tel" placeholder="Ex: (11) 99999-9999" />

        <x-input name="email" label="E-mail" value="{{ $instrutor->email }}" type="email" required />

        <x-input name="especialidade" label="Especialidade" value="{{ $instrutor->especialidade }}" required />

        <x-textarea name="biografia" label="Biografia" value="{{ $instrutor->biografia }}" rows="5" />

        @if($instrutor->foto)
            <div class="mb-4">
                <label class="block font-bold mb-1 text-brand">Foto Atual</label>
                <img src="{{ asset('storage/' . $instrutor->foto) }}" alt="{{ $instrutor->nome }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
            </div>
        @endif

        <x-input name="foto" label="Nova Foto (deixe vazio para manter a atual)" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" help="JPEG, PNG, JPG ou WEBP. Máx. 2MB." />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.instrutores.index')" label="Voltar" />
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">Atualizar Instrutor</button>
        </div>
    </form>
</div>
@endsection
