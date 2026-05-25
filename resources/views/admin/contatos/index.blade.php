@extends('layouts.main')
@section('titulo', 'Mensagens de Contato')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Contatos']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Mensagens de Contato</h1>
    </div>

    <x-alert message="{{ session('success') }}" />

    @if($contatos->isEmpty())
        <div class="text-center py-5">
            <x-icon name="envelope" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
            <p class="text-gray-500 text-lg">Nenhuma mensagem recebida ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Status</th>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Email</th>
                        <th class="p-3 text-left text-sm font-semibold">Mensagem</th>
                        <th class="p-3 text-left text-sm font-semibold">Data</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($contatos as $contato)
                        <tr class="hover:bg-gray-50 transition-colors {{ $contato->lido ? '' : 'font-semibold' }}">
                            <td class="p-3 text-sm">
                                @if($contato->lido)
                                    <span class="text-green-600"><x-icon name="check-circle" class="w-4 h-4" /> Lido</span>
                                @else
                                    <span class="text-yellow-600"><x-icon name="envelope" class="w-4 h-4" /> Novo</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm">{{ $contato->nome }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $contato->email }}</td>
                            <td class="p-3 text-sm text-gray-500 max-w-xs truncate">{{ $contato->mensagem }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $contato->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.contatos.show', $contato->id) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-blue-400 text-blue-600 hover:bg-blue-50 text-sm">
                                        <x-icon name="eye" class="w-4 h-4" /> Ver
                                    </a>
                                    <form action="{{ route('admin.contatos.destroy', $contato->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir esta mensagem?',function(){f.submit();}); return false;">
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

        <div class="mt-4">
            {{ $contatos->links() }}
        </div>
    @endif
</div>
@endsection
