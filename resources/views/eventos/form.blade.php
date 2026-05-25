@extends('layouts.main')

@php
    $isEdit = (bool) $evento->id_evento;
    
    // Dynamic text based on role & state
    if ($isArtisan) {
        $pageTitle = $isEdit ? 'Editar Proposta de Evento' : 'Propor Novo Evento / Oficina';
        $formAction = $isEdit ? route('artesan.eventos.atualizar', $evento->id_evento) : route('artesan.eventos.salvar');
        $nameLabel = 'Nome do Evento / Oficina';
        $namePlaceholder = 'Ex: Oficina Prática de Cerâmica Figurativa';
        $descLabel = 'Descrição Completa e Cronograma';
        $descPlaceholder = 'Detalhe o que os participantes aprenderão, pré-requisitos e materiais incluídos';
        $btnText = $isEdit ? 'Salvar Proposta' : 'Propor Evento';
        $infoText = 'Ao cadastrar, o evento iniciará como "Aguardando Aprovação". O administrador revisará os dados antes de incluí-lo na agenda oficial.';
    } else {
        $pageTitle = $isEdit ? 'Editar Evento' : 'Cadastrar Novo Evento';
        $formAction = $isEdit ? route('eventos.update', $evento->id_evento) : route('eventos.store');
        $nameLabel = 'Nome do Evento';
        $namePlaceholder = 'Digite o nome do evento';
        $descLabel = 'Descrição';
        $descPlaceholder = 'Descreva o evento';
        $btnText = $isEdit ? 'Atualizar Evento' : 'Salvar Evento';
        $infoText = null;
    }
@endphp

@section('titulo', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10 transition-all duration-300 hover:shadow-xl">
    <h1 class="text-2xl font-bold text-brand mb-2">{{ $pageTitle }}</h1>
    <p class="text-gray-500 mb-6 text-sm">Organize as datas, local e capacidades para engajar a comunidade da Associação.</p>

    {{-- Artisan Proposal Note --}}
    @if ($infoText)
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3.5 rounded-xl mb-6 flex items-start gap-3 shadow-sm animate-pulse-once">
            <x-icon name="info" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
            <div class="text-sm">
                <strong class="font-bold block text-blue-900 mb-0.5">Revisão de Eventos:</strong>
                {{ $infoText }}
            </div>
        </div>
    @endif

    {{-- Validation Errors summary --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl mb-6 relative shadow-sm" id="err-box">
            <button type="button" class="absolute top-3 right-3 text-red-500 hover:text-red-700 cursor-pointer border-0 bg-transparent" onclick="this.parentElement.remove()">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="warning" class="w-5 h-5 text-red-600" />
                <strong class="font-bold text-red-900">Ops! Verifique os erros listados abaixo:</strong>
            </div>
            <ul class="list-disc pl-5 mt-1 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-4">
            <x-input name="nome" label="{{ $nameLabel }}" value="{{ old('nome', $evento->nome) }}" placeholder="{{ $namePlaceholder }}" required />

            <x-textarea name="descricao" label="{{ $descLabel }}" placeholder="{{ $descPlaceholder }}" rows="4" required>{{ old('descricao', $evento->descricao) }}</x-textarea>

            {{-- Tipo, Capacidade e Valor --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-select name="tipo_evento" label="Tipo de Evento" 
                        :options="[
                            'workshop' => 'Oficina / Workshop', 
                            'feira' => 'Feira de Artesanato', 
                            'exposicao' => 'Exposição', 
                            'palestra' => 'Palestra / Roda de Conversa', 
                            'lancamento' => 'Lançamento', 
                            'outro' => 'Outro'
                        ]" 
                        value="{{ old('tipo_evento', $evento->tipo_evento) }}" placeholder="Selecione..." required />
                </div>
                <div>
                    <x-input name="capacidade_maxima" label="Capacidade Máxima" type="number" placeholder="Ex: 15" value="{{ old('capacidade_maxima', $evento->capacidade_maxima ?? 10) }}" required />
                </div>
                <div>
                    <x-input name="valor_inscricao" label="Valor da Inscrição (R$)" type="number" step="0.01" placeholder="0,00" value="{{ old('valor_inscricao', $evento->valor_inscricao ?? '0.00') }}" required />
                </div>
            </div>

            {{-- Datas e Local --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input name="data_inicio" label="Data e Hora de Início" type="datetime-local" value="{{ old('data_inicio', $evento->data_inicio ? \Carbon\Carbon::parse($evento->data_inicio)->format('Y-m-d\TH:i') : '') }}" required />
                </div>
                <div>
                    <x-input name="data_fim" label="Data e Hora do Fim" type="datetime-local" value="{{ old('data_fim', $evento->data_fim ? \Carbon\Carbon::parse($evento->data_fim)->format('Y-m-d\TH:i') : '') }}" required />
                </div>
                <div>
                    <x-input name="local" label="Local" placeholder="Ex: Sede da Associação, Sala A" value="{{ old('local', $evento->local) }}" required />
                </div>
            </div>

            {{-- Admin Exclusive Fields (Status & Instrutor) --}}
            @if (!$isArtisan)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-amber-50/50 p-4 rounded-xl border border-amber-100">
                    <div>
                        <x-select name="status" label="Status do Evento" 
                            :options="[
                                'planejado' => 'Planejado', 
                                'confirmado' => 'Confirmado', 
                                'em_andamento' => 'Em Andamento', 
                                'concluido' => 'Concluído', 
                                'cancelado' => 'Cancelado'
                            ]" 
                            value="{{ old('status', $evento->status ?? 'planejado') }}" required />
                    </div>
                    <div>
                        <label for="nome_instrutor" class="block font-bold mb-1 text-brand text-sm">Instrutor Responsável</label>
                        <input type="text" list="instrutores_list" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" id="nome_instrutor" name="nome_instrutor"
                               placeholder="Digite o nome ou selecione..." value="{{ old('nome_instrutor', $evento->instrutor?->nome) }}">
                        <datalist id="instrutores_list">
                            @foreach ($instrutores as $instrutor)
                                <option value="{{ $instrutor->nome }}">
                            @endforeach
                        </datalist>
                        <small class="text-[11px] text-gray-500 mt-1 block">Se digitado um novo instrutor, o perfil dele será criado automaticamente.</small>
                    </div>
                </div>
            @endif

            {{-- Banner Image Upload --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mt-2">
                <label class="block font-bold mb-2 text-brand text-sm">Imagem de Capa (Banner/Folder)</label>
                <p class="text-xs text-gray-500 mb-3">Envie um banner promocional em JPG, PNG até 2MB.</p>

                @if ($isEdit && $evento->imagem)
                    <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200 inline-block">
                        <small class="text-gray-400 block mb-1.5 font-bold uppercase tracking-wider text-[10px]">Banner Atual:</small>
                        <x-image src="{{ $evento->imagem }}" alt="Banner atual" class="rounded-lg shadow-sm border" style="max-height: 120px; max-width: 250px; object-fit: cover;" />
                    </div>
                @endif

                <div class="flex gap-2">
                    <input class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror text-sm" type="file" id="imagem" name="imagem" accept="image/*" onchange="window.validarTamanhoImagem(this) && previewImagem(this)">
                </div>

                <div id="preview-imagem" class="mt-4 hidden">
                    <small class="text-brand-light block mb-1 font-bold uppercase tracking-wider text-[10px]">Nova Capa Selecionada:</small>
                    <img id="preview-img" class="rounded-lg border shadow-sm" style="max-height: 180px; max-width: 100%;">
                </div>
                @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <x-back-button :route="route('evento')" label="Voltar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer">
                <x-icon name="check-circle" class="w-4 h-4" /> {{ $btnText }}
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImagem(input) {
        const preview = document.getElementById('preview-imagem');
        const img = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { 
                img.src = e.target.result; 
                preview.classList.remove('hidden'); 
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
            img.src = '';
        }
    }
</script>
@endsection
