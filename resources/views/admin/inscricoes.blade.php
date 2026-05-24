@extends('layouts.main')
@section('titulo', 'Inscrições em Eventos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Inscrições']]" />
    <h1 class="font-bold mb-4 text-2xl text-brand">Inscrições em Eventos</h1>

    @if($inscricoes->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-book-open text-5xl text-gray-500 mb-3 block"></i>
            <p class="text-gray-500 text-lg">Nenhuma inscrição realizada ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Evento</th>
                        <th class="p-3 text-left text-sm font-semibold">Cliente</th>
                        <th class="p-3 text-left text-sm font-semibold">Data Inscrição</th>
                        <th class="p-3 text-left text-sm font-semibold">Pagamento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($inscricoes as $inscricao)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $inscricao->evento->nome }}</td>
                            <td class="p-3 text-sm">{{ $inscricao->cliente->nome ?? 'N/D' }}</td>
                            <td class="p-3 text-sm">{{ $inscricao->data_inscricao?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="p-3 text-sm">
                                <x-badge type="{{ $inscricao->isPago() ? 'success' : ($inscricao->isPendente() ? 'pending' : 'danger') }}">{{ $inscricao->status_pagamento }}</x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $inscricoes->links() }}
        </div>
    @endif
</div>
@endsection
