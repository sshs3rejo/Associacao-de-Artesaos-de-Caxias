@extends('layouts.main')

@section('titulo', 'Cadastrar Produto')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Cadastrar Novo Produto</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative" id="err-create-produto">
            <button type="button" class="absolute top-2 right-2 text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-input name="nome" label="Nome do Produto" placeholder="Ex: Camisa Bege Casual" required />

        <x-textarea name="descricao" label="Descrição" placeholder="Descreva brevemente o produto" rows="4" required />

        {{-- Categoria / Preço / Quantidade --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-select name="id_categoria" label="Categoria" :options="\App\Models\CategoriasProdutos::getHierarchicalList()" placeholder="Selecione..." required />
            </div>
            <div>
                <x-input name="preco" label="Preço (R$)" type="number" step="0.01" placeholder="0,00" required />
            </div>
            <div>
                <x-input name="quantidade" label="Quantidade em Estoque" type="number" placeholder="0" required />
            </div>
        </div>

        {{-- Imagem --}}
        <div class="mb-6">
            <label class="block font-bold mb-1 text-brand">Imagem do Produto</label>
            <div class="flex gap-2 mb-2">
                <input class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror" type="file" id="imagem" name="imagem" accept="image/*" capture="environment" onchange="previewImagem(this)">
                <button type="button" class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600" onclick="document.getElementById('imagem').click()" title="Tirar Foto">
                    <x-icon name="camera" class="w-4 h-4" />
                </button>
            </div>
            <div id="preview-imagem" class="mt-2" style="display:none;">
                <img id="preview-img" class="rounded-lg border" style="max-height:200px;max-width:100%;">
            </div>
            @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Botões --}}
        <div class="flex justify-between items-center">
            <x-back-button :route="route('produtos')" label="Cancelar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Salvar Produto</button>
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
            reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            img.src = '';
        }
    }
</script>
@endsection
