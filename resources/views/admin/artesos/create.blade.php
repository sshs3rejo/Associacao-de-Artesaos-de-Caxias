@extends('layouts.main')
@section('titulo', 'Novo Artesão')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Novo Artesão</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
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

    <form action="{{ route('admin.artesao.store') }}" method="POST">
        @csrf

        <x-input name="name" label="Nome completo" placeholder="Ex: Maria Silva" required />

        <x-input name="email" label="Email" type="email" placeholder="Ex: maria@artesao.com" required />

        <x-input name="password" label="Senha" type="password" placeholder="Mínimo 6 caracteres" required />

        <x-input name="password_confirmation" label="Confirmar senha" type="password" placeholder="Repita a senha" required />

        <x-input name="specialty" label="Especialidade" placeholder="Ex: Cerâmica Artesanal" />

        <x-textarea name="bio" label="Biografia" placeholder="Conte um pouco sobre o artesão..." rows="3" />

        <div class="mb-4">
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_public" value="0">
                <input type="checkbox" name="is_public" value="1" checked class="rounded border-gray-300 text-brand focus:ring-brand-light">
                <span class="text-sm font-semibold text-brand">Perfil público</span>
            </label>
        </div>

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.artesao')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">
                <x-icon name="check-circle" class="w-4 h-4" /> Salvar Artesão
            </button>
        </div>
    </form>
</div>
@endsection