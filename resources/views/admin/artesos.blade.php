@extends('layouts.main')
@section('titulo', 'Gerenciar Artesãos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Artesãos']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Gerenciar Artesãos</h1>
        <a href="{{ route('admin.artesao.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold shadow-sm transition no-underline">
            <x-icon name="plus" class="w-4 h-4" /> Novo Artesão
        </a>
    </div>

    @if($artesos->isEmpty())
        <div class="text-center py-5">
            <x-icon name="users" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
            <p class="text-gray-500 text-lg">Nenhum artesão cadastrado ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Email</th>
                        <th class="p-3 text-left text-sm font-semibold">Especialidade</th>
                        <th class="p-3 text-left text-sm font-semibold">Status</th>
                        <th class="p-3 text-left text-sm font-semibold">Perfil Público</th>
                        <th class="p-3 text-left text-sm font-semibold">Cadastro</th>
                        <th class="p-3 text-left text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($artesos as $artesao)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $artesao->name }}</td>
                            <td class="p-3 text-sm">{{ $artesao->email }}</td>
                            <td class="p-3 text-sm">{{ $artesao->artisanProfile?->specialty ?? '-' }}</td>
                            <td class="p-3 text-sm">
                                @if($artesao->artisanProfile?->isApproved())
                                    <x-badge type="success">Aprovado</x-badge>
                                @else
                                    <x-badge type="pending">Pendente</x-badge>
                                @endif
                            </td>
                            <td class="p-3 text-sm">
                                @if($artesao->artisanProfile?->is_public)
                                    <x-badge type="info">Público</x-badge>
                                @else
                                    <x-badge type="inactive">Privado</x-badge>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ $artesao->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    <a href="{{ route('admin.artesao.show', $artesao) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-blue-400 text-blue-600 hover:bg-blue-50 text-xs" title="Ver">
                                        <x-icon name="eye" class="w-3 h-3" />
                                    </a>
                                    <a href="{{ route('admin.artesao.edit', $artesao) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-xs" title="Editar">
                                        <x-icon name="pencil" class="w-3 h-3" />
                                    </a>
                                    <form action="{{ route('admin.artesao.destroy', $artesao) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-red-400 text-red-500 hover:bg-red-50 text-xs" title="Excluir" onclick="var f=this.closest('form'); showConfirm('Remover artesão {{ $artesao->name }} permanentemente?',function(){f.submit();});">
                                            <x-icon name="trash" class="w-3 h-3" />
                                        </button>
                                    </form>
                                    @if(!$artesao->artisanProfile?->isApproved())
                                        <form action="{{ route('admin.artesao.aprovar', $artesao) }}" method="POST">
                                            @csrf
                                            <button type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline text-white bg-green-500 hover:bg-green-600 text-xs" onclick="var f=this.closest('form'); showConfirm('Aprovar {{ $artesao->name }}?',function(){f.submit();},'Aprovar artesão')">
                                                <x-icon name="check" class="w-3 h-3" />
                                            </button>
                                        </form>
                                    @endif
                                    @if($artesao->isActive())
                                        <form action="{{ route('admin.artesao.rejeitar', $artesao) }}" method="POST">
                                            @csrf
                                            <button type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-xs" onclick="confirmarExclusao(this)">
                                                <x-icon name="times" class="w-3 h-3" />
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.artesao.ativar', $artesao) }}" method="POST">
                                            @csrf
                                            <button type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline text-white bg-green-500 hover:bg-green-600 text-xs" onclick="var f=this.closest('form'); showConfirm('Reativar este artesão?',function(){f.submit();},'Reativar artesão')">
                                                <x-icon name="check" class="w-3 h-3" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
