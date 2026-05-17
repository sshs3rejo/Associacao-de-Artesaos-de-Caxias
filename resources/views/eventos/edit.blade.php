@extends('layouts.main')

@section('titulo', 'Editar Evento')

@section('content')
<style>
    /* ==== Estilo visual do formulário ==== */
    .evento-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        padding: 40px 50px;
    }

    .evento-container h1 {
        color: var(--brand-color, #5C3A2C);
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 600;
        color: var(--brand-color, #5C3A2C);
    }

    .form-control, .form-select {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 12px;
        transition: 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--highlight-color, #8C5E47);
        box-shadow: 0 0 4px rgba(140,94,71,0.3);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-primary {
        background-color: var(--brand-color, #5C3A2C);
        border: none;
        font-weight: bold;
        transition: 0.3s;
        padding: 12px 28px;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background-color: var(--highlight-color, #8C5E47);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #bbb;
        border: none;
        color: #fff;
        font-weight: bold;
        padding: 12px 28px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .btn-secondary:hover {
        background-color: #999;
        transform: translateY(-2px);
    }

    .img-preview {
        display: block;
        max-width: 200px;
        border-radius: 8px;
        margin-top: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    select.form-select,
    select.form-select option {
        color: #333 !important;
        background-color: #fff !important;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: none;
        color: #842029;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
</style>

<div class="evento-container">
    <h1>Editar Evento</h1>

    {{-- Exibe erros de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Verifique os campos abaixo:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário --}}
    <form action="{{ route('eventos.update', $evento->id_evento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Evento</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome"
                   value="{{ old('nome', $evento->nome) }}" required>
            @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Descrição --}}
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="4" required>{{ old('descricao', $evento->descricao) }}</textarea>
            @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Linha 1: Tipo / Data Inicio / Data Fim --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="tipo_evento" class="form-label">Tipo do Evento</label>
                <select class="form-select @error('tipo_evento') is-invalid @enderror" id="tipo_evento" name="tipo_evento" required>
                    <option value="">Selecione...</option>
                    <option value="feira" {{ old('tipo_evento', $evento->tipo_evento) == 'feira' ? 'selected' : '' }}>Feira</option>
                    <option value="exposicao" {{ old('tipo_evento', $evento->tipo_evento) == 'exposicao' ? 'selected' : '' }}>Exposição</option>
                    <option value="workshop" {{ old('tipo_evento', $evento->tipo_evento) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="lancamento" {{ old('tipo_evento', $evento->tipo_evento) == 'lancamento' ? 'selected' : '' }}>Lançamento</option>
                    <option value="palestra" {{ old('tipo_evento', $evento->tipo_evento) == 'palestra' ? 'selected' : '' }}>Palestra</option>
                    <option value="outro" {{ old('tipo_evento', $evento->tipo_evento) == 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="data_inicio" class="form-label">Data e Hora de Início</label>
                <input type="datetime-local" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio"
                       value="{{ old('data_inicio', optional($evento->data_inicio)->format('Y-m-d\TH:i')) }}" required>
                @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="data_fim" class="form-label">Data e Hora de Fim</label>
                <input type="datetime-local" class="form-control @error('data_fim') is-invalid @enderror" id="data_fim" name="data_fim"
                       value="{{ old('data_fim', optional($evento->data_fim)->format('Y-m-d\TH:i')) }}" required>
                @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Linha 2: Local / Capacidade / Valor / Status --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="local" class="form-label">Local</label>
                <input type="text" class="form-control @error('local') is-invalid @enderror" id="local" name="local"
                       value="{{ old('local', $evento->local) }}" required>
                @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="capacidade_maxima" class="form-label">Capacidade Máx.</label>
                <input type="number" min="1" class="form-control @error('capacidade_maxima') is-invalid @enderror" id="capacidade_maxima" name="capacidade_maxima"
                       value="{{ old('capacidade_maxima', $evento->capacidade_maxima) }}" required>
                @error('capacidade_maxima') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2 mb-3">
                <label for="valor_inscricao" class="form-label">Valor (R$)</label>
                <input type="number" step="0.01" min="0" class="form-control @error('valor_inscricao') is-invalid @enderror" id="valor_inscricao" name="valor_inscricao"
                       value="{{ old('valor_inscricao', $evento->valor_inscricao) }}" required>
                @error('valor_inscricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="planejado" {{ old('status', $evento->status) == 'planejado' ? 'selected' : '' }}>Planejado</option>
                    <option value="confirmado" {{ old('status', $evento->status) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="em_andamento" {{ old('status', $evento->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluido" {{ old('status', $evento->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="cancelado" {{ old('status', $evento->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>

        {{-- Instrutor --}}
        <div class="mb-3">
            <label for="nome_instrutor" class="form-label">Instrutor Responsável</label>
            <input type="text" list="instrutores_list" class="form-control" id="nome_instrutor" name="nome_instrutor" 
                   placeholder="Digite o nome ou selecione..." value="{{ old('nome_instrutor', optional($evento->instrutor)->nome) }}">
            @error('nome_instrutor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <datalist id="instrutores_list">
                @foreach ($instrutores as $instrutor)
                    <option value="{{ $instrutor->nome }}">
                @endforeach
            </datalist>
            <small class="text-muted mt-1 d-block">Se o instrutor não existir, ele será criado automaticamente.</small>
        </div>

        {{-- Imagem --}}
        <div class="mb-4">
            <label for="imagem" class="form-label">Imagem do Evento</label>
            <input class="form-control @error('imagem') is-invalid @enderror" type="file" id="imagem" name="imagem" accept="image/*">
            @error('imagem') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if ($evento->imagem)
                <small class="text-muted d-block mt-2">Imagem atual:</small>
                <img src="{{ asset('storage/' . $evento->imagem) }}" alt="Imagem atual do evento" class="img-preview">
            @endif
        </div>

        {{-- Botões --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('evento') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Atualizar Evento
            </button>
        </div>
    </form>
</div>
@endsection
