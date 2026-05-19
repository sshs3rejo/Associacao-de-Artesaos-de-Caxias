@extends('layouts.main')

@section('titulo', 'Editar Produto - Artesão')

@section('content')
<style>
    .produto-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        padding: 40px 50px;
    }

    .produto-container h1 {
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
</style>

<div class="container py-5">
    <div class="produto-container">
        <h1>Editar Produto</h1>

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

        <form action="{{ route('artesan.produtos.atualizar', $produto->id_produto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome"
                       placeholder="Ex: Escultura de Leão em Madeira de Lei" value="{{ old('nome', $produto->nome) }}" required>
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição da Peça e Processo Criativo</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao"
                          placeholder="Fale um pouco sobre o material e a técnica artesanal utilizada" rows="4">{{ old('descricao', $produto->descricao) }}</textarea>
                @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="id_categoria" class="form-label">Categoria</label>
                    <select class="form-select @error('id_categoria') is-invalid @enderror" id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $produto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nome_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="preco" class="form-label">Preço Unitário (R$)</label>
                    <input type="number" step="0.01" class="form-control @error('preco') is-invalid @enderror" id="preco" name="preco"
                           placeholder="0,00" value="{{ old('preco', $produto->preco) }}" required>
                    @error('preco') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="quantidade" class="form-label">Estoque</label>
                    <input type="number" class="form-control @error('quantidade') is-invalid @enderror" id="quantidade" name="quantidade"
                           placeholder="1" value="{{ old('quantidade', $produto->estoque?->quantidade ?? 0) }}" required>
                    @error('quantidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="imagem" class="form-label">Imagem do Produto</label>
                @if($produto->imagem)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem atual" class="rounded shadow-sm" style="max-height: 120px; object-fit: cover;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" id="remover_imagem" name="remover_imagem" value="1">
                            <label class="form-check-label text-danger small" for="remover_imagem">Remover imagem atual</label>
                        </div>
                    </div>
                @endif
                <input class="form-control @error('imagem') is-invalid @enderror" type="file" id="imagem" name="imagem" accept="image/*">
                @error('imagem') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('produtos') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary text-white">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection