@extends('layouts.main')
@section('titulo', 'Instrutores')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Instrutores']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Instrutores</h1>
        <a href="{{ route('admin.instrutores.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <i class="fas fa-plus"></i> Novo Instrutor
        </a>
    </div>

    <x-alert message="{{ session('success') }}" />

    @if($instrutores->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-chalkboard-teacher text-5xl text-gray-500 mb-3 block"></i>
            <p class="text-gray-500 text-lg">Nenhum instrutor cadastrado ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Especialidade</th>
                        <th class="p-3 text-left text-sm font-semibold">E-mail</th>
                        <th class="p-3 text-left text-sm font-semibold">Telefone</th>
                        <th class="p-3 text-left text-sm font-semibold">Eventos</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($instrutores as $instrutor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $instrutor->nome }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $instrutor->especialidade }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $instrutor->email }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $instrutor->telefone ?? '—' }}</td>
                            <td class="p-3 text-sm">
                                @if($instrutor->eventos_count > 0)
                                    <span class="text-gray-600">{{ $instrutor->eventos_count }} eventos</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.instrutores.edit', $instrutor->id_instrutor) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                        <i class="fa fa-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.instrutores.destroy', $instrutor->id_instrutor) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="return confirm('Tem certeza de que deseja excluir o instrutor {{ $instrutor->nome }}?')">
                                            <i class="fa fa-trash"></i> Remover
                                        </button>
                                    </form>
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
