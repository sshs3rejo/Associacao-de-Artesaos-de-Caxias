@extends('layouts.main')
@section('titulo', 'Inscrições em Eventos')

@section('content')
<div class="container-fluid px-4 py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Inscrições em Eventos</h1>

    @if($inscricoes->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-text display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Nenhuma inscrição realizada ainda.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3">Evento</th>
                        <th class="p-3">Cliente</th>
                        <th class="p-3">Data Inscrição</th>
                        <th class="p-3">Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscricoes as $inscricao)
                        <tr>
                            <td class="fw-semibold p-3">{{ $inscricao->evento->nome }}</td>
                            <td class="p-3">{{ $inscricao->cliente->nome ?? 'N/D' }}</td>
                            <td class="p-3">{{ $inscricao->data_inscricao?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="p-3">
                                <span class="badge {{ $inscricao->isPago() ? 'bg-success' : ($inscricao->isPendente() ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $inscricao->status_pagamento }}
                                </span>
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
