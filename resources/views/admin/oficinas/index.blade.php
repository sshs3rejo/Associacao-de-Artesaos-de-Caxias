@extends('layouts.main')
@section('titulo', 'Oficinas')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Oficinas']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Oficinas</h1>
        <a href="{{ route('admin.oficinas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <x-icon name="plus" class="w-4 h-4" /> Nova Oficina
        </a>
    </div>

    <x-alert message="{{ session('success') }}" />

    @if($oficinas->isEmpty())
        <div class="text-center py-5">
            <x-icon name="chalkboard" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
            <p class="text-gray-500 text-lg">Nenhuma oficina cadastrada ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Instrutor</th>
                        <th class="p-3 text-left text-sm font-semibold">Período</th>
                        <th class="p-3 text-left text-sm font-semibold">Vagas</th>
                        <th class="p-3 text-left text-sm font-semibold">Inscrições</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($oficinas as $oficina)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $oficina->nome }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $oficina->instrutor?->nome ?? '—' }}</td>
                            <td class="p-3 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($oficina->data_inicio)->format('d/m/Y') }}
                                @if($oficina->data_fim)
                                    ~ {{ \Carbon\Carbon::parse($oficina->data_fim)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ $oficina->vagas ?? '—' }}</td>
                            <td class="p-3 text-sm">
                                @if($oficina->inscricoes_count > 0)
                                    <span class="text-gray-600">{{ $oficina->inscricoes_count }} inscrições</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.oficinas.edit', $oficina->id) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                        <x-icon name="pencil" class="w-4 h-4" /> Editar
                                    </a>
                                    <form action="{{ route('admin.oficinas.destroy', $oficina->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir a oficina {{ $oficina->nome }}?',function(){f.submit();}); return false;">
                                            <x-icon name="trash" class="w-4 h-4" /> Remover
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $oficinas->links() }}
        </div>
    @endif
</div>
@endsection
