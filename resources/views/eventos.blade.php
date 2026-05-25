@extends('layouts.main')
@section('titulo', 'Eventos - ' . config('association.name_short'))

@auth
    @if(auth()->user()->isAdmin())
        @section('content')
        <div class="w-full px-4 py-5">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
                <x-icon name="arrow-left" class="w-3 h-3" /> Voltar ao Painel
            </a>
            <div class="flex items-center justify-between mb-4">
                <h1 class="font-bold mb-0 text-brand text-2xl">Gerenciar Eventos</h1>
                <a href="{{ route('eventos.create') }}" class="inline-flex items-center gap-2 text-white font-bold px-4 py-2 rounded-full shadow-sm bg-brand hover:bg-brand-dark transition no-underline">
                    <x-icon name="plus" class="w-4 h-4" /> Novo Evento
                </a>
            </div>

            <x-alert type="success" :message="session('success')" />

            @if($eventos->isEmpty())
                <div class="text-center py-5">
                    <x-icon name="calendar" class="w-14 h-14 text-gray-400 mb-3 block" />
                    <p class="text-gray-500 text-xl">Nenhum evento cadastrado.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-xl shadow-sm overflow-hidden">
                        <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                            <tr>
                                <th class="p-3 text-left">Capa</th>
                                <th class="p-3 text-left">Nome</th>
                                <th class="p-3 text-left">Proponente</th>
                                <th class="p-3 text-left">Tipo</th>
                                <th class="p-3 text-left">Data Início</th>
                                <th class="p-3 text-left">Local</th>
                                <th class="p-3 text-left">Vagas</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($eventos as $evento)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <x-image :src="$evento->imagem" alt="$evento->nome" class="rounded shadow-sm w-14 h-10 object-cover" />
                                    </td>
                                    <td class="font-semibold p-3 text-brand-light">{{ $evento->nome }}</td>
                                    <td class="p-3">{{ $evento->artisan?->name ?? 'Admin' }}</td>
                                    <td class="p-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-500 text-white">{{ ucfirst($evento->tipo_evento) }}</span></td>
                                    <td class="p-3 text-sm">{{ $evento->data_inicio?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td class="p-3 text-sm">{{ $evento->local }}</td>
                                    <td class="p-3">{{ $evento->vagas_disponiveis }}/{{ $evento->capacidade_maxima }}</td>
                                    <td class="p-3">
                                        @if($evento->is_approved)
                                            <x-badge type="success">Aprovado</x-badge>
                                        @else
                                            <x-badge type="pending">Pendente</x-badge>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-400 hover:text-white transition no-underline">
                                                <x-icon name="pencil" class="w-4 h-4" /> Editar
                                            </a>
                                            <form action="{{ route('eventos.destroy', $evento->id_evento) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-red-400 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" onclick="var f=this.closest('form'); showConfirm('Remover evento {{ $evento->nome }} permanentemente?',function(){f.submit();}); return false;">
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
                <div class="mt-4 flex justify-center">
                    {{ $eventos->links() }}
                </div>
            @endif
        </div>
        @endsection

    @elseif(auth()->user()->isArtisan())
        @section('content')
        <div class="max-w-7xl mx-auto px-4 py-5">
            <a href="{{ route('artesan.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
                <x-icon name="arrow-left" class="w-3 h-3" /> Voltar ao Painel
            </a>
            <div class="flex items-center justify-between mb-4">
                <h1 class="font-bold mb-0 text-brand text-2xl">Meus Eventos</h1>
                <a href="{{ route('artesan.eventos.criar') }}" class="inline-flex items-center gap-2 text-white font-bold px-4 py-2 rounded-full shadow-sm bg-brand hover:bg-brand-dark transition no-underline">
                    <x-icon name="plus" class="w-4 h-4" /> Propor Novo Evento
                </a>
            </div>

            <x-alert type="success" :message="session('success')" />

            <div>
                <div class="flex gap-2 mb-4 border-b border-gray-200 pb-2">
                    <button id="evt-tab-propostas" onclick="switchEventoTab('propostas')" class="rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer bg-brand text-accent">
                        Minhas Propostas de Eventos
                    </button>
                    <button id="evt-tab-inscricoes" onclick="switchEventoTab('inscricoes')" class="rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer bg-transparent text-brand border border-brand hover:bg-gray-100">
                        Eventos que Vou Participar
                    </button>
                </div>

                <div id="evt-content-propostas">
                    @if($eventosPropostos->isEmpty())
                        <div class="text-center py-5">
                            <x-icon name="calendar" class="w-14 h-14 text-gray-400 mb-3 block" />
                            <p class="text-gray-500 text-xl">Você ainda não propôs nenhum evento ou oficina.</p>
                            <p class="text-gray-400">Clique no botão "+ Propor Novo Evento" para começar!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($eventosPropostos as $evento)
                                <div class="bg-white shadow-sm rounded-xl h-full overflow-hidden flex flex-col">
                                    <div class="overflow-hidden relative h-36" style="background-color: #eee;">
                                        <x-image :src="$evento->imagem" alt="$evento->nome" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="p-4 flex flex-col flex-1">
                                        <div class="flex justify-between items-start gap-2 mb-2">
                                            <h5 class="font-bold mb-0 truncate text-brand" title="{{ $evento->nome }}">{{ $evento->nome }}</h5>
                                            @if($evento->is_approved)
                                                <x-badge type="success">Ativo</x-badge>
                                            @else
                                                <x-badge type="pending">Pendente</x-badge>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 mb-2">
                                            <x-icon name="calendar" class="w-4 h-4 mr-1" />
                                            {{ $evento->data_inicio?->format('d/m/Y H:i') ?? 'Data a definir' }}
                                        </div>
                                        <div class="text-sm text-gray-500 mb-3">
                                            <x-icon name="map-marker" class="w-4 h-4 mr-1" />
                                            {{ $evento->local }}
                                        </div>
                                        <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-200">
                                            <span class="font-bold text-price">
                                                {{ $evento->isGratuito() ? 'Gratuito' : 'R$ ' . number_format($evento->valor_inscricao, 2, ',', '.') }}
                                            </span>
                                            <span class="text-sm text-gray-500">Vagas: {{ $evento->vagas_disponiveis }}/{{ $evento->capacidade_maxima }}</span>
                                        </div>
                                        <div class="flex gap-2 mt-3 pt-2 border-t border-gray-200">
                                            <x-card-actions :edit-route="route('artesan.eventos.editar', $evento->id_evento)" :delete-route="route('artesan.eventos.deletar', $evento->id_evento)" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div id="evt-content-inscricoes" style="display: none;">
                    @if($inscricoes->isEmpty())
                        <div class="text-center py-5">
                            <x-icon name="calendar" class="w-14 h-14 text-gray-400 mb-3 block" />
                            <p class="text-gray-500 text-xl">Você não está inscrito em nenhum evento.</p>
                            <a href="{{ route('evento') }}" class="inline-block text-white font-bold px-4 py-2 rounded-full shadow-sm" style="background-color: #7a2f1f;">
                                Ver Eventos Disponíveis
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($inscricoes as $inscricao)
                                <div class="bg-white shadow-sm rounded-xl h-full flex flex-col">
                                    <div class="p-4 flex flex-col flex-1">
                                        <h5 class="font-bold mb-2 text-brand">{{ $inscricao->evento?->nome ?? 'Evento removido' }}</h5>
                                        <div class="text-sm text-gray-500 mb-3">
                                            <x-icon name="calendar" class="w-4 h-4 mr-1" />
                                            {{ $inscricao->evento?->data_inicio?->format('d/m/Y H:i') ?? 'Data a definir' }}
                                        </div>
                                        <x-badge type="{{ $inscricao->isPago() ? 'success' : 'pending' }}">{{ $inscricao->status_pagamento }}</x-badge>
                                        <x-badge type="info">{{ $inscricao->evento?->status ?? '—' }}</x-badge>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <script>
                function switchEventoTab(tab) {
                    const btnP = document.getElementById('evt-tab-propostas');
                    const btnI = document.getElementById('evt-tab-inscricoes');
                    const contentP = document.getElementById('evt-content-propostas');
                    const contentI = document.getElementById('evt-content-inscricoes');
                    const active = 'bg-brand text-accent';
                    const inactive = 'bg-transparent text-brand border border-brand hover:bg-gray-100';
                    if (tab === 'propostas') {
                        btnP.className = 'rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer ' + active;
                        btnI.className = 'rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer ' + inactive;
                        contentP.style.display = 'block';
                        contentI.style.display = 'none';
                    } else {
                        btnP.className = 'rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer ' + inactive;
                        btnI.className = 'rounded-full font-bold px-4 py-2 text-sm transition cursor-pointer ' + active;
                        contentP.style.display = 'none';
                        contentI.style.display = 'block';
                    }
                }
            </script>
        </div>
        @endsection

    @endif
@endauth

@if(auth()->guest() || (auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->isArtisan()))
    @section('content')
    <div class="w-full px-4 lg:px-5 py-5">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
            <x-icon name="arrow-left" class="w-3 h-3" /> Voltar
        </a>
        <div class="mb-5">
            <div class="text-center">
                <h1 class="font-bold text-4xl mb-3 text-brand">Nossos Eventos</h1>
                <p class="text-gray-500 text-xl mx-auto" style="max-width: 600px;">
                    Fique por dentro das feiras, exposições e oficinas organizadas pela Associação dos Artesãos de Caxias.
                </p>
                <hr class="mx-auto mt-4" style="width: 80px; height: 3px; background-color: #c85a3a; opacity: 1; border: none; border-radius: 2px;">
            </div>
        </div>

        <div class="flex justify-center">
            <div class="w-full max-w-7xl">
                @if($eventos->isEmpty())
                    <div class="text-center py-5 bg-white rounded-xl shadow-sm" style="border: 1px dashed rgba(122, 47, 31, 0.2);">
                        <x-icon name="calendar" class="w-14 h-14 mb-3" style="color: #d1b8a4;" />
                        <h3 class="font-semibold text-brand">Nenhum evento no momento</h3>
                        <p class="text-gray-500 text-xl">Fique de olho! Em breve teremos novidades.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($eventos as $evento)
                            <div class="bg-white h-full rounded-xl overflow-hidden flex flex-col shadow-sm hover:-translate-y-1 hover:shadow-lg transition" style="border: 1px solid rgba(122,47,31,0.1);">
                                <div class="relative" style="height: 200px; background-color: #f5f1ed;">
                                    @if($evento->imagem)
                                        <x-image src="{{ $evento->imagem }}" alt="{{ $evento->nome }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-icon name="calendar" class="w-12 h-12" style="color: #d1b8a4;" />
                                        </div>
                                    @endif
                                    <div class="absolute top-0 right-0 m-3">
                                        <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold shadow-sm text-white" style="background-color: #c85a3a;">
                                            {{ $evento->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4 flex flex-col items-center text-center flex-1">
                                    <h4 class="font-bold mb-3 truncate text-brand" title="{{$evento->nome}}">
                                        {{$evento->nome}}
                                    </h4>
                                    <div class="mt-auto flex flex-col items-center gap-2">
                                        <div class="text-lg font-bold text-price">
                                            @if($evento->isGratuito())
                                                <x-icon name="tag" class="w-4 h-4 mr-1" /> Gratuito
                                            @else
                                                R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-transparent border-0 p-3 pt-0 mt-auto">
                                    <a href="{{route('eventos.show', $evento->id_evento)}}" class="block w-full text-center rounded-full py-2 font-bold uppercase tracking-wider text-sm bg-brand text-accent hover:bg-brand-dark hover:-translate-y-0.5 hover:shadow-md transition" style="letter-spacing: 1px;">
                                        Mais Detalhes
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endsection
@endif
