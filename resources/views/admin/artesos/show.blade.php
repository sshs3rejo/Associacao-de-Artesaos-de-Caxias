@extends('layouts.main')
@section('titulo', 'Detalhes do Artesão')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Artesãos', route('admin.artesao')], [$user->name]]" />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-brand">{{ $user->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.artesao.edit', $user) }}" class="inline-flex items-center gap-1 px-4 py-2 border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-400 hover:text-white transition no-underline font-semibold">
                <x-icon name="pencil" class="w-4 h-4" /> Editar
            </a>
            <form action="{{ route('admin.artesao.destroy', $user) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" class="inline-flex items-center gap-1 px-4 py-2 border border-red-400 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition font-semibold" onclick="var f=this.closest('form'); showConfirm('Remover artesão {{ $user->name }} permanentemente?',function(){f.submit();});">
                    <x-icon name="trash" class="w-4 h-4" /> Excluir
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-bold text-brand mb-3">Informações do Usuário</h3>
            <table class="w-full text-sm">
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Nome:</td><td>{{ $user->name }}</td></tr>
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Email:</td><td>{{ $user->email }}</td></tr>
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Cadastro:</td><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Status:</td>
                    <td>@if($user->is_active) <x-badge type="success">Ativo</x-badge> @else <x-badge type="inactive">Inativo</x-badge> @endif</td>
                </tr>
            </table>
        </div>
        <div>
            <h3 class="font-bold text-brand mb-3">Perfil de Artesão</h3>
            <table class="w-full text-sm">
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Especialidade:</td><td>{{ $user->artisanProfile?->specialty ?? '—' }}</td></tr>
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Aprovação:</td>
                    <td>@if($user->artisanProfile?->isApproved()) <x-badge type="success">Aprovado</x-badge> @else <x-badge type="pending">Pendente</x-badge> @endif</td>
                </tr>
                <tr><td class="py-1 font-semibold text-gray-500 pr-4">Perfil Público:</td>
                    <td>@if($user->artisanProfile?->is_public) <x-badge type="info">Sim</x-badge> @else <x-badge type="inactive">Não</x-badge> @endif</td>
                </tr>
                @if($user->artisanProfile?->bio)
                <tr><td class="py-1 font-semibold text-gray-500 pr-4 align-top">Bio:</td><td>{{ $user->artisanProfile->bio }}</td></tr>
                @endif
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.artesao') }}" class="inline-flex items-center gap-1 text-sm text-brand hover:text-brand-dark font-medium">
            <x-icon name="arrow-left" class="w-3 h-3" /> Voltar para lista
        </a>
    </div>
</div>
@endsection