@extends('layouts.main')

@section('titulo', 'Propor Evento - Artesão')

@section('content')
<style>
    .evento-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        padding: 40px 50px;
    }

    .evento-container h1 {
        color: var(--brand-color, #7a2f1f);
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 600;
        color: var(--brand-color, #7a2f1f);
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #ccc;
        transition: 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #8b5a3c;
        box-shadow: 0 0 4px rgba(139,90,60,0.3);
    }

    .btn-primary {
        background-color: #7a2f1f;
        border: none;
        font-weight: bold;
        padding: 12px 28px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #8b5a3c;
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

    .alert-info {
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        border: 0;
        background-color: #e8f4fd;
        color: #0b4f84;
    }
</style>

<div class="container py-5">
    <div class="evento-container">
        <h1>Propor Novo Evento / Oficina</h1>

        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Revisão de Eventos:</strong> Ao cadastrar, o evento iniciará como **"Aguardando Aprovação"**. O administrador revisará os dados do local, data e instrutor antes de incluí-lo na agenda oficial da Associação.
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <strong>Ops!</strong> Corrija os erros abaixo:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ ucfirst($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('artesan.eventos.salvar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nome --}}
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Evento / Oficina</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome"
                       placeholder="Ex: Oficina Prática de Cerâmica Figurativa" value="{{ old('nome') }}" required>
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Descrição --}}
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição Completa e Cronograma</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao"
                          placeholder="Detalhe o que os participantes aprenderão, pré-requisitos e materiais incluídos" rows="4">{{ old('descricao') }}</textarea>
                @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Tipo, Capacidade e Inscrição --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="tipo_evento" class="form-label">Tipo de Evento</label>
                    <select class="form-select @error('tipo_evento') is-invalid @enderror" id="tipo_evento" name="tipo_evento" required>
                        <option value="">Selecione...</option>
                        <option value="workshop" {{ old('tipo_evento') == 'workshop' ? 'selected' : '' }}>Oficina / Workshop</option>
                        <option value="feira" {{ old('tipo_evento') == 'feira' ? 'selected' : '' }}>Feira de Artesanato</option>
                        <option value="exposicao" {{ old('tipo_evento') == 'exposicao' ? 'selected' : '' }}>Exposição</option>
                        <option value="palestra" {{ old('tipo_evento') == 'palestra' ? 'selected' : '' }}>Palestra / Roda de Conversa</option>
                        <option value="lancamento" {{ old('tipo_evento') == 'lancamento' ? 'selected' : '' }}>Lançamento</option>
                        <option value="outro" {{ old('tipo_evento') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="capacidade_maxima" class="form-label">Capacidade Máxima</label>
                    <input type="number" class="form-control @error('capacidade_maxima') is-invalid @enderror" id="capacidade_maxima" name="capacidade_maxima"
                           placeholder="Ex: 15" value="{{ old('capacidade_maxima', 10) }}" required>
                    @error('capacidade_maxima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="valor_inscricao" class="form-label">Valor da Inscrição (R$)</label>
                    <input type="number" step="0.01" class="form-control @error('valor_inscricao') is-invalid @enderror" id="valor_inscricao" name="valor_inscricao"
                           placeholder="0,00 (Deixe 0 se gratuito)" value="{{ old('valor_inscricao', '0.00') }}" required>
                    @error('valor_inscricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Datas e Local --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="data_inicio" class="form-label">Data e Hora de Início</label>
                    <input type="datetime-local" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio"
                           value="{{ old('data_inicio') }}" required>
                    @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="data_fim" class="form-label">Data e Hora do Fim</label>
                    <input type="datetime-local" class="form-control @error('data_fim') is-invalid @enderror" id="data_fim" name="data_fim"
                           value="{{ old('data_fim') }}" required>
                    @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="local" class="form-label">Local do Evento</label>
                    <input type="text" class="form-control @error('local') is-invalid @enderror" id="local" name="local"
                           placeholder="Ex: Sede da Associação, Sala A" value="{{ old('local') }}" required>
                    @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Imagem --}}
            <div class="mb-4">
                <label for="imagem" class="form-label">Imagem de Capa (Banner/Folder)</label>
                <input class="form-control @error('imagem') is-invalid @enderror" type="file" id="imagem" name="imagem" accept="image/*">
                @error('imagem') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('artesan.eventos') }}" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary text-white">Propor Evento</button>
            </div>
        </form>
    </div>
</div>
@endsection
