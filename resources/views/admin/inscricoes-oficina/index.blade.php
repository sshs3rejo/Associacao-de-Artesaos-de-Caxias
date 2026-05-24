@extends('layouts.main')
@section('titulo', 'Inscrições em Oficinas')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Inscrições em Oficinas']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Inscrições em Oficinas</h1>
    </div>

    <x-alert message="{{ session('success') }}" />

    @if($inscricoes->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-user-plus text-5xl text-gray-500 mb-3 block"></i>
            <p class="text-gray-500 text-lg">Nenhuma inscrição encontrada.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Cliente</th>
                        <th class="p-3 text-left text-sm font-semibold">Oficina</th>
                        <th class="p-3 text-left text-sm font-semibold">Data da Inscrição</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($inscricoes as $inscricao)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $inscricao->cliente?->nome ?? '—' }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $inscricao->oficina?->nome ?? '—' }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($inscricao->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="p-3 text-sm text-right">
                                <form action="{{ route('admin.inscricoes-oficina.destroy', $inscricao->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="return confirm('Tem certeza de que deseja cancelar esta inscrição?')">
                                        <i class="fa fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $inscricoes->links() }}
        </div>
    @endif
</div>
@endsection
