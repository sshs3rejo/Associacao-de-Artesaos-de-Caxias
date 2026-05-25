@extends('layouts.main')

@section('titulo', 'Novo Usuário')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Novo Usuário</h1>

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

    <form action="{{ route('admin.usuarios.store') }}" method="POST">
        @csrf

        <x-input name="name" label="Nome" placeholder="Nome completo" required />

        <x-input name="email" label="Email" type="email" placeholder="email@exemplo.com" required />

        <x-input name="password" label="Senha" type="password" placeholder="Mínimo 8 caracteres" required />

        <x-input name="password_confirmation" label="Confirmar Senha" type="password" placeholder="Repita a senha" required />

        <x-select name="role" label="Função" :options="['user' => 'Comprador', 'artisan' => 'Artesão', 'admin' => 'Admin']" required />

        <div class="flex justify-between items-center">
            <x-back-button :route="route('admin.usuarios')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Usuário</button>
        </div>
    </form>
</div>
@endsection
