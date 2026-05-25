@extends('layouts.main')

@section('titulo', 'Propor Produto - Artesão')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Propor Novo Produto</h1>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <x-icon name="info" class="w-5 h-5" />
        <div>
            <strong>Nota de Aprovação:</strong> Ao salvar, seu produto será cadastrado como **"Aguardando Aprovação"**. O administrador da Associação revisará os dados e imagem antes de torná-lo público na vitrine.
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative" id="err-box">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-700 hover:text-red-900 cursor-pointer border-0 bg-transparent">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os erros abaixo:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artesan.produtos.salvar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-input name="nome" label="Nome do Produto" placeholder="Ex: Escultura de Leão em Madeira de Lei" required />

        <x-textarea name="descricao" label="Descrição da Peça e Processo Criativo" placeholder="Fale um pouco sobre o material e a técnica artesanal utilizada" rows="4" required />

        {{-- Categoria / Preço / Quantidade --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-select name="id_categoria" label="Categoria" :options="$categorias->pluck('nome_categoria', 'id_categoria')->toArray()" placeholder="Selecione..." required />
            </div>
            <div>
                <x-input name="preco" label="Preço Unitário (R$)" type="number" step="0.01" placeholder="0,00" required />
            </div>
            <div>
                <x-input name="quantidade" label="Estoque Inicial" type="number" placeholder="1" value="{{ old('quantidade', 1) }}" required />
            </div>
        </div>

        {{-- Imagem --}}
        <div class="mb-6">
            <label class="block font-bold mb-1 text-brand">Imagem do Produto (Bela e com boa iluminação)</label>
            <div class="flex gap-2 mb-2">
                <input class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror" type="file" id="imagem" name="imagem" accept="image/*" capture="environment" onchange="previewImagem(this)">
                <button type="button" class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600" onclick="document.getElementById('imagem').click()" title="Tirar Foto">
                    <x-icon name="camera" class="w-5 h-5" />
                </button>
            </div>
            <div id="preview-imagem" class="mt-2" style="display:none;">
                <img id="preview-img" class="rounded-lg border" style="max-height:200px;max-width:100%;">
            </div>
            @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Botões --}}
        <div class="flex justify-between items-center">
            <x-back-button :route="route('produtos')" label="Voltar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200"><x-icon name="check-circle" class="w-4 h-4" /> Propor Produto</button>
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
