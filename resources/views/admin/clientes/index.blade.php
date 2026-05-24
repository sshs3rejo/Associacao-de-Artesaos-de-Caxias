@extends('layouts.main')
@section('titulo', 'Clientes')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Clientes']]" />
    <h1 class="font-bold mb-4 text-2xl text-brand">Clientes</h1>

    @if($clientes->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-users text-5xl text-gray-500 mb-3 block"></i>
            <p class="text-gray-500 text-lg">Nenhum cliente encontrado.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Email</th>
                        <th class="p-3 text-left text-sm font-semibold">Telefone</th>
                        <th class="p-3 text-left text-sm font-semibold">Usuário</th>
                        <th class="p-3 text-left text-sm font-semibold">Compras</th>
                        <th class="p-3 text-left text-sm font-semibold">Cadastro</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($clientes as $c)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $c->nome }}</td>
                            <td class="p-3 text-sm">{{ $c->email ?? '—' }}</td>
                            <td class="p-3 text-sm">{{ $c->telefone ?? '—' }}</td>
                            <td class="p-3 text-sm">
                                @if($c->user)
                                    <span class="text-gray-700">{{ $c->user->name }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm">{{ $c->vendas_count }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-right">
                                <a href="{{ route('admin.clientes.show', $c->id_cliente) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-blue-400 text-blue-600 hover:bg-blue-50 text-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $clientes->links() }}
        </div>
    @endif
</div>
@endsection
