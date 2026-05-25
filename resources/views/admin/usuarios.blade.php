@extends('layouts.main')
@section('titulo', 'Usuários do Sistema')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Usuários']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Usuários do Sistema</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <x-icon name="plus" class="w-4 h-4" /> Novo Usuário
        </a>
    </div>

    @if($usuarios->isEmpty())
        <div class="text-center py-5">
            <x-icon name="users" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
            <p class="text-gray-500 text-lg">Nenhum usuário encontrado.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Email</th>
                        <th class="p-3 text-left text-sm font-semibold">Função (Role)</th>
                        <th class="p-3 text-left text-sm font-semibold">Status</th>
                        <th class="p-3 text-left text-sm font-semibold">Artesão</th>
                        <th class="p-3 text-left text-sm font-semibold">Cadastro</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($usuarios as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $u->name }}</td>
                            <td class="p-3 text-sm">{{ $u->email }}</td>
                            <td class="p-3 text-sm">
                                <form action="{{ route('admin.usuarios.change-role', $u->id) }}" method="POST" class="flex items-center gap-1 m-0">
                                    @csrf
                                    <select name="role" class="w-full px-3 py-1 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent" style="max-width: 130px; font-size: 0.85rem;" onchange="this.form.submit()">
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>Comprador</option>
                                        <option value="artisan" {{ $u->role === 'artisan' ? 'selected' : '' }}>Artesão</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-3 text-sm">
                                @if($u->isActive())
                                    <x-badge type="success">Ativo</x-badge>
                                @else
                                    <x-badge type="danger">Inativo</x-badge>
                                @endif
                            </td>
                            <td class="p-3 text-sm">
                                @if($u->artisanProfile)
                                    @if($u->artisanProfile->isApproved())
                                        <x-badge type="success">Aprovado</x-badge>
                                    @else
                                        <x-badge type="pending">Pendente</x-badge>
                                    @endif
                                @else
                                    <span class="text-gray-500 text-sm">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.usuarios.toggle-status', $u->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @if($u->isActive())
                                            <button type="submit" class="inline-block px-3 py-1 rounded-full font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm" title="Bloquear Usuário">
                                                <x-icon name="times" class="w-4 h-4" /> Bloquear
                                            </button>
                                        @else
                                            <button type="submit" class="inline-block px-3 py-1 rounded-full font-semibold text-center no-underline border border-green-500 text-green-600 hover:bg-green-50 text-sm" title="Reativar Usuário">
                                                <x-icon name="check-circle" class="w-4 h-4" /> Ativar
                                            </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.usuarios.destroy', $u->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-block px-3 py-1 rounded-full font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir permanentemente o usuário {{ $u->name }} do sistema?',function(){f.submit();}); return false;" title="Excluir Usuário">
                                            <x-icon name="trash" class="w-4 h-4" /> Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection
