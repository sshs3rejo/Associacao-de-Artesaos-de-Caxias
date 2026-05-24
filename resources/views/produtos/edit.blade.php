@extends('layouts.main')

@section('titulo', 'Editar Produto')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <h1 class="text-2xl font-bold text-brand mb-6">Editar Produto</h1>

    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative">
            <button type="button" class="absolute top-2 right-2 text-red-700 hover:text-red-900" @click="show = false">
                <i class="fas fa-times"></i>
            </button>
            <strong class="font-bold">Ops!</strong> Corrija os campos abaixo:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ ucfirst($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produtos.update', $produto->id_produto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-input name="nome" label="Nome do Produto" value="{{ $produto->nome }}" required />

        <x-textarea name="descricao" label="Descrição" rows="4" required value="{{ $produto->descricao }}" />

        {{-- Categoria / Preço / Quantidade --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-select name="id_categoria" label="Categoria" :options="$categorias->pluck('nome_categoria', 'id_categoria')->toArray()" value="{{ $produto->id_categoria }}" placeholder="Selecione..." required />
            </div>
            <div>
                <x-input name="preco" label="Preço (R$)" type="number" step="0.01" value="{{ $produto->preco }}" required />
            </div>
            <div>
                <x-input name="quantidade" label="Quantidade em Estoque" type="number" value="{{ $produto->estoque->quantidade ?? 0 }}" required />
            </div>
        </div>

        {{-- Imagem --}}
        <div class="mb-6">
            <label class="block font-bold mb-1 text-brand">Imagem do Produto</label>
            <div class="flex gap-2 mb-2">
                <input class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none @error('imagem') border-red-500 @enderror" type="file" id="imagem" name="imagem" accept="image/*" capture="environment" onchange="previewImagem(this)">
                <button type="button" class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600" onclick="document.getElementById('imagem').click()" title="Tirar Foto">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div id="preview-imagem" class="mt-2" style="display:none;">
                <img id="preview-img" class="rounded-lg border" style="max-height:200px;max-width:100%;">
            </div>
            @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror

            @if ($produto->imagem)
                <div id="container-imagem-atual" class="mt-4">
                    <small class="text-gray-500 block mb-2">Imagem atual:</small>
                    <div class="relative inline-block mt-2">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem atual do produto" class="img-preview mt-0" id="img-atual" loading="lazy">

                        <div id="area-acoes-imagem">
                            <button type="button" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 z-10" onclick="marcarParaRemover()" title="Remover Imagem">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="remover_imagem" id="input-remover-imagem" value="0">

                    <div id="aviso-remocao" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 mt-2 rounded-lg hidden text-sm max-w-[250px] flex items-center gap-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Será removida ao salvar.
                        <button type="button" class="underline font-bold ml-1 hover:text-yellow-900" onclick="desfazerRemocao()">Desfazer</button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Botões --}}
        <div class="flex justify-between items-center">
            <x-back-button :route="route('produtos')" label="Voltar" />
            <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold transition duration-200">Atualizar Produto</button>
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
        document.getElementById('area-acoes-imagem').classList.add('hidden');
        document.getElementById('aviso-remocao').classList.remove('hidden');
    }

    function desfazerRemocao() {
        document.getElementById('input-remover-imagem').value = '0';
        document.getElementById('img-atual').style.opacity = '1';
        document.getElementById('img-atual').style.filter = 'none';
        document.getElementById('area-acoes-imagem').classList.remove('hidden');
        document.getElementById('aviso-remocao').classList.add('hidden');
    }

    document.getElementById('imagem').addEventListener('change', function() {
        if (this.files.length > 0) {
            desfazerRemocao();
        }
    });

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
