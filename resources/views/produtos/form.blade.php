@extends('layouts.main')

@php
    $isEdit = (bool) $produto->id_produto;
    
    // Dynamic text based on role & state
    if ($isArtisan) {
        $pageTitle = $isEdit ? 'Editar Proposta de Produto' : 'Propor Novo Produto';
        $formAction = $isEdit ? route('artesan.produtos.atualizar', $produto->id_produto) : route('artesan.produtos.salvar');
        $descLabel = 'Descrição da Peça e Processo Criativo';
        $descPlaceholder = 'Fale um pouco sobre o material e a técnica artesanal utilizada';
        $btnText = $isEdit ? 'Salvar Proposta' : 'Propor Produto';
        $infoText = 'Ao salvar, o produto será cadastrado como "Aguardando Aprovação". A administração revisará os dados antes de torná-lo público.';
    } else {
        $pageTitle = $isEdit ? 'Editar Produto' : 'Cadastrar Novo Produto';
        $formAction = $isEdit ? route('admin.produtos.update', $produto->id_produto) : route('admin.produtos.store');
        $descLabel = 'Descrição';
        $descPlaceholder = 'Descreva brevemente o produto';
        $btnText = $isEdit ? 'Atualizar Produto' : 'Salvar Produto';
        $infoText = null;
    }
@endphp

@section('titulo', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10 transition-all duration-300 hover:shadow-xl">
    <h1 class="text-2xl font-bold text-brand mb-2">{{ $pageTitle }}</h1>
    <p class="text-gray-500 mb-6 text-sm">Preencha com atenção todas as informações requeridas abaixo para o seu produto.</p>

    {{-- Artisan Information Box --}}
    @if ($infoText)
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3.5 rounded-xl mb-6 flex items-start gap-3 shadow-sm animate-pulse-once">
            <x-icon name="info" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
            <div class="text-sm">
                <strong class="font-bold block text-blue-900 mb-0.5">Nota de Aprovação:</strong>
                {{ $infoText }}
            </div>
        </div>
    @endif

    {{-- Error Summary UI --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl mb-6 relative shadow-sm" id="err-box">
            <button type="button" class="absolute top-3 right-3 text-red-500 hover:text-red-700 cursor-pointer border-0 bg-transparent" onclick="this.parentElement.remove()">
                <x-icon name="times" class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="warning" class="w-5 h-5 text-red-600" />
                <strong class="font-bold text-red-900">Ops! Corrija os erros listados abaixo:</strong>
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
            <x-input name="nome" label="Nome do Produto" value="{{ old('nome', $produto->nome) }}" placeholder="Ex: Escultura de Leão em Madeira" required />

            <x-textarea name="descricao" label="{{ $descLabel }}" placeholder="{{ $descPlaceholder }}" rows="4">{{ old('descricao', $produto->descricao) }}</x-textarea>

            {{-- Grid Categoria / Preço / Estoque --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <x-select name="id_categoria" label="Categoria" :options="\App\Models\CategoriasProdutos::getAllCached()->pluck('nome_categoria', 'id_categoria')" value="{{ old('id_categoria', $produto->id_categoria) }}" placeholder="Selecione..." required />
                        </div>
                        <button type="button" onclick="abrirModalCategoria()" class="mb-4 px-3 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center justify-center" title="Nova Categoria">
                            <i class="fas fa-plus" style="font-size:16px"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <x-input name="preco" label="Preço (R$)" type="number" step="0.01" value="{{ old('preco', $produto->preco) }}" placeholder="0,00" required />
                </div>
                <div>
                    <x-input name="quantidade" label="Estoque" type="number" placeholder="1" value="{{ old('quantidade', $produto->estoque->quantidade ?? 1) }}" required />
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mt-2">
                <label class="block font-bold mb-2 text-brand text-sm">Imagem do Produto</label>
                <p class="text-xs text-gray-500 mb-3">Recomendamos uma foto bem iluminada do produto. Formatos aceitos: JPG, PNG até 2MB.</p>

                @if ($isEdit && $produto->imagem)
                    <div id="container-imagem-atual" class="mb-4 bg-white p-3 rounded-lg border border-gray-200 inline-block">
                        <small class="text-gray-400 block mb-1.5 font-bold uppercase tracking-wider text-[10px]">Imagem Atual:</small>
                        <div class="relative inline-block">
                            <x-image src="{{ $produto->imagem }}" alt="Imagem atual" class="rounded-lg shadow-sm border" style="max-height: 120px; max-width: 250px; object-fit: cover;" id="img-atual" />
                            
                            <div id="area-acoes-imagem" class="absolute top-2 right-2">
                                <button type="button" class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full shadow-md hover:bg-red-700 z-10 border-0 cursor-pointer transition" onclick="marcarParaRemover()" title="Remover Imagem">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="remover_imagem" id="input-remover-imagem" value="0">

                        <div id="aviso-remocao" class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 mt-2 rounded-lg hidden text-xs items-center gap-1">
                            <x-icon name="warning" class="w-4 h-4 mr-1 text-yellow-600" /> A imagem atual será removida ao salvar.
                            <button type="button" class="underline font-bold ml-1 hover:text-yellow-950 border-0 bg-transparent cursor-pointer" onclick="desfazerRemocao()">Desfazer</button>
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    <input class="w-full border bg-white rounded-lg px-4 py-2.5 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm {{ $errors->has('imagem') ? 'border-red-500' : 'border-gray-300' }}" type="file" id="imagem" name="imagem" accept="image/*" onchange="window.validarTamanhoImagem(this) && previewImagem(this)">
                    <button type="button" class="px-4 py-2.5 border border-gray-300 bg-white rounded-lg hover:bg-gray-100 text-gray-600 cursor-pointer transition flex items-center justify-center" onclick="document.getElementById('imagem').click()" title="Tirar Foto">
                        <x-icon name="camera" class="w-5 h-5" />
                    </button>
                </div>

                <div id="preview-imagem" class="mt-4 hidden">
                    <small class="text-brand-light block mb-1 font-bold uppercase tracking-wider text-[10px]">Nova Imagem Selecionada:</small>
                    <img id="preview-img" class="rounded-lg border shadow-sm" style="max-height: 180px; max-width: 100%;">
                </div>
                @error('imagem') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            @if(!$isArtisan)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-2 text-brand text-sm">Artesão</label>
                    <select name="id_artesan" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm">
                        <option value="">Nenhum</option>
                        @foreach($artesaos as $artesao)
                            <option value="{{ $artesao->id }}" {{ old('id_artesan', $produto->id_artesan) == $artesao->id ? 'selected' : '' }}>{{ $artesao->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Atribua este produto a um artesão (opcional).</p>
                </div>
                <div class="flex items-end pb-3">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="hidden" name="mostrar_artesao" value="0">
                        <input type="checkbox" name="mostrar_artesao" value="1" class="w-5 h-5 rounded border-gray-300 text-brand focus:ring-brand-light cursor-pointer" {{ old('mostrar_artesao', $produto->mostrar_artesao ?? true) ? 'checked' : '' }}>
                        <span class="text-sm font-bold text-brand">Mostrar artesão na página pública</span>
                    </label>
                </div>
            </div>
            @endif
        </div>

        {{-- Actions Buttons --}}
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <x-back-button :route="route('produtos')" label="Voltar" />
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer">
                <x-icon name="check-circle" class="w-4 h-4" /> {{ $btnText }}
            </button>
        </div>
    </form>
</div>

{{-- Modal Categorias --}}
<div id="modal-nova-categoria" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="backdrop-filter: blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 relative">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 rounded-t-2xl">
            <h3 class="text-lg font-bold text-brand m-0 flex items-center gap-2">
                <i class="fas fa-plus" style="font-size:18px"></i>
 Criar Nova Categoria
            </h3>
            <button type="button" onclick="fecharModalCategoria()" class="text-gray-400 hover:text-gray-700 cursor-pointer border-0 bg-transparent flex items-center p-1">
                <i class="fas fa-times" style="font-size:20px"></i>
            </button>
        </div>

        <div class="p-6">
            <p class="text-gray-500 text-sm mb-5">Crie uma nova categoria para classificar o produto.</p>
            <div id="erro-modal-categoria" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm hidden"></div>
            <div>
                <label for="modal-categoria-nome" class="block font-bold mb-1 text-brand text-sm">Nome da Categoria</label>
                <input type="text" id="modal-categoria-nome" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" placeholder="Ex: Artesanato em Cerâmica" required />
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="fecharModalCategoria()" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-semibold text-sm transition cursor-pointer bg-white">
                    Cancelar
                </button>
                <button type="button" onclick="salvarCategoriaAjax()" class="px-5 py-2.5 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center gap-2 text-sm" id="btn-salvar-categoria">
                    <i class="fas fa-check-circle" style="font-size:16px"></i>
 Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window._quickStoreUrl = '{{ route("admin.categorias.quick-store") }}';
</script>
@endsection

@section('scripts')
<script>
    function marcarParaRemover() {
        const inputRemove = document.getElementById('input-remover-imagem');
        const imgAtual = document.getElementById('img-atual');
        const actions = document.getElementById('area-acoes-imagem');
        const warning = document.getElementById('aviso-remocao');
        
        if (inputRemove) inputRemove.value = '1';
        if (imgAtual) {
            imgAtual.style.opacity = '0.3';
            imgAtual.style.filter = 'grayscale(100%)';
        }
        if (actions) actions.classList.add('hidden');
        if (warning) {
            warning.classList.remove('hidden');
            warning.classList.add('flex');
        }
    }

    function desfazerRemocao() {
        const inputRemove = document.getElementById('input-remover-imagem');
        const imgAtual = document.getElementById('img-atual');
        const actions = document.getElementById('area-acoes-imagem');
        const warning = document.getElementById('aviso-remocao');
        
        if (inputRemove) inputRemove.value = '0';
        if (imgAtual) {
            imgAtual.style.opacity = '1';
            imgAtual.style.filter = 'none';
        }
        if (actions) actions.classList.remove('hidden');
        if (warning) {
            warning.classList.add('hidden');
            warning.classList.remove('flex');
        }
    }

    // Se selecionar uma nova imagem, cancela a exclusão da atual automaticamente
    const fileInput = document.getElementById('imagem');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                desfazerRemocao();
            }
        });
    }

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

    /* ── Modal Categorias ── */

    function abrirModalCategoria() {
        document.getElementById('modal-categoria-nome').value = '';
        document.getElementById('erro-modal-categoria').classList.add('hidden');
        const modal = document.getElementById('modal-nova-categoria');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fecharModalCategoria() {
        const modal = document.getElementById('modal-nova-categoria');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function salvarCategoriaAjax() {
        const nome = document.getElementById('modal-categoria-nome').value.trim();
        const erroDiv = document.getElementById('erro-modal-categoria');
        const btn = document.getElementById('btn-salvar-categoria');

        if (!nome) {
            erroDiv.textContent = 'Informe o nome da categoria.';
            erroDiv.classList.remove('hidden');
            return;
        }

        erroDiv.classList.add('hidden');
        btn.disabled = true;
        btn.textContent = 'Salvando...';

        fetch(window._quickStoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nome_categoria: nome }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                erroDiv.textContent = data.message || 'Erro ao criar categoria.';
                erroDiv.classList.remove('hidden');
            }
        })
        .catch(function(err) {
            erroDiv.textContent = 'Erro ao criar categoria. Veja o console (F12).';
            erroDiv.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Salvar';
        });
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }
</script>

@endsection
