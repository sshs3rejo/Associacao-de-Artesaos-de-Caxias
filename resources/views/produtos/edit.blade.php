@extends('layouts.main')

@section('title', 'Editar Produto')

@section('content')
<style>
    /* Mesmo estilo do create */
    .produto-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        padding: 40px 50px;
    }

    .produto-container h1 {
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
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #ccc;
        transition: 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--highlight-color, #8C5E47);
        box-shadow: 0 0 4px rgba(140,94,71,0.3);
    }

    .img-preview {
        display: block;
        max-width: 200px;
        border-radius: 8px;
        margin-top: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .btn-primary {
        background-color: var(--brand-color, #5C3A2C);
        border: none;
        font-weight: bold;
        padding: 12px 28px;
        border-radius: 10px;
        transition: 0.3s;
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
</style>

<div class="produto-container">
    <h1>Editar Produto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Corrija os campos abaixo:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produtos.update', $produto->id_produto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome"
                   value="{{ old('nome', $produto->nome) }}" required>
        </div>

        {{-- Descrição --}}
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="4" required>{{ old('descricao', $produto->descricao) }}</textarea>
        </div>

        {{-- Categoria / Preço / Quantidade --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="id_categoria" class="form-label">Categoria</label>
                <select class="form-select" id="id_categoria" name="id_categoria" required>
                    <option value="">Selecione...</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $produto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->nome_categoria }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="preco" class="form-label">Preço (R$)</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco"
                       value="{{ old('preco', $produto->preco) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="quantidade" class="form-label">Quantidade em Estoque</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade"
                       value="{{ old('quantidade', $produto->estoque->quantidade ?? 0) }}" required>
            </div>
        </div>

        {{-- Imagem --}}
        <div class="mb-4">
            <label for="imagem" class="form-label">Imagem do Produto</label>
            <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">

            @if ($produto->imagem)
                <div id="container-imagem-atual" class="mt-3">
                    <small class="text-muted d-block mb-2">Imagem atual:</small>
                    <div class="position-relative d-inline-block mt-2">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem atual do produto" class="img-preview mt-0" id="img-atual">
                        
                        <div id="area-acoes-imagem">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 8px; right: 8px; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3);" onclick="marcarParaRemover()" title="Remover Imagem">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="remover_imagem" id="input-remover-imagem" value="0">

                    <div id="aviso-remocao" class="alert alert-warning py-2 mt-2 d-none" style="font-size: 0.85rem; max-width: 250px;">
                        <i class="fa fa-exclamation-triangle me-1"></i> Será removida ao salvar.
                        <button type="button" class="btn btn-link btn-sm p-0 ms-1 text-decoration-none fw-bold" onclick="desfazerRemocao()">Desfazer</button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Botões --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('produtos') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function marcarParaRemover() {
        document.getElementById('input-remover-imagem').value = '1';
        document.getElementById('img-atual').style.opacity = '0.3';
        document.getElementById('img-atual').style.filter = 'grayscale(100%)';
        document.getElementById('area-acoes-imagem').classList.add('d-none');
        document.getElementById('aviso-remocao').classList.remove('d-none');
    }

    function desfazerRemocao() {
        document.getElementById('input-remover-imagem').value = '0';
        document.getElementById('img-atual').style.opacity = '1';
        document.getElementById('img-atual').style.filter = 'none';
        document.getElementById('area-acoes-imagem').classList.remove('d-none');
        document.getElementById('aviso-remocao').classList.add('d-none');
    }

    // Se o usuário selecionar um novo arquivo, desfaz a marcação de remoção manual
    document.getElementById('imagem').addEventListener('change', function() {
        if (this.files.length > 0) {
            desfazerRemocao();
        }
    });
</script>
@endsection
