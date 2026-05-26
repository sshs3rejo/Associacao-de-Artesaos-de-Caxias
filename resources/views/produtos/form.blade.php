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

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

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
                <p class="text-xs text-gray-500 mb-3">Recomendamos uma foto bem iluminada do produto. Formatos aceitos: JPG, PNG.</p>

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
                    <input class="w-full border bg-white rounded-lg px-4 py-2.5 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm {{ $errors->has('imagem') ? 'border-red-500' : 'border-gray-300' }}" type="file" id="imagem" name="imagem" accept="image/*" onchange="comprimirEExibir(this)">
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

{{-- Modal Corte de Imagem --}}
<div id="modal-corte-imagem" class="fixed inset-0 z-50 hidden flex-col sm:items-center sm:justify-center bg-black/60 sm:p-4">
    <div class="bg-white w-full h-full sm:h-auto sm:max-w-3xl sm:rounded-2xl sm:max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 rounded-t-2xl shrink-0">
            <h3 class="text-lg font-bold text-brand m-0 flex items-center gap-2">
                <i class="fas fa-crop-alt" style="font-size:18px"></i>
                 Ajustar Imagem
            </h3>
            <button type="button" onclick="fecharModalCorte()" class="text-gray-400 hover:text-gray-700 cursor-pointer border-0 bg-transparent flex items-center p-1">
                <i class="fas fa-times" style="font-size:20px"></i>
            </button>
        </div>

        <div class="p-4 flex-1 overflow-hidden min-h-0 flex flex-col">
            <p class="text-gray-500 text-sm mb-3">Arraste para reposicionar, use a roda do mouse para zoom.</p>
            <div class="flex-1 bg-black/5 rounded-xl overflow-hidden relative min-h-0" id="crop-container">
                <img id="crop-img" class="max-w-full" alt="Imagem para corte">
            </div>
        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 shrink-0">
            <button type="button" onclick="fecharModalCorte()" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-semibold text-sm transition cursor-pointer bg-white">
                Cancelar
            </button>
            <button type="button" onclick="confirmarCorte()" class="px-6 py-2.5 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center gap-2 text-sm" id="btn-confirmar-corte">
                <i class="fas fa-check-circle" style="font-size:16px"></i>
                 Confirmar Corte
            </button>
        </div>
    </div>
</div>

<script>
window._quickStoreUrl = '{{ route("admin.categorias.quick-store") }}';
</script>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let cropper = null;
    let cropFileInput = null;

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

    function comprimirEExibir(input) {
        if (!input || !input.files || !input.files[0]) return;
        if (typeof desfazerRemocao === 'function') desfazerRemocao();
        cropFileInput = input;
        abrirModalCorte(input.files[0]);
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

    /* ── Modal Corte de Imagem ── */

    function abrirModalCorte(file) {
        if (!file) return;

        var modal = document.getElementById('modal-corte-imagem');
        var img = document.getElementById('crop-img');

        if (cropper) { cropper.destroy(); cropper = null; }
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        img.onload = function () {
            setTimeout(function () {
                try {
                    cropper = new Cropper(img, {
                        aspectRatio: NaN,
                        viewMode: 1,
                        dragMode: 'move',
                        zoomable: true,
                        zoomOnTouch: true,
                        zoomOnWheel: true,
                        wheelZoomRatio: 0.05,
                        toggleDragModeOnDblclick: false,
                        minCropBoxWidth: 30,
                        minCropBoxHeight: 30,
                        background: true,
                        responsive: true,
                        restore: false,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        autoCropArea: 0.8,
                    });
                } catch (e) {
                    cropper = null;
                    comprimirDireto(cropFileInput);
                }
            }, 150);
        };
        img.onerror = function () {
            fecharModalCorte();
            comprimirDireto(cropFileInput);
        };

        img.src = '';
        var reader = new FileReader();
        reader.onerror = function () {
            fecharModalCorte();
            if (window.mostrarToast) window.mostrarToast('Erro ao ler arquivo', 'error');
        };
        reader.onload = function (e) { img.src = e.target.result; };
        reader.readAsDataURL(file);
    }

    function fecharModalCorte() {
        if (cropper) { cropper.destroy(); cropper = null; }
        document.getElementById('crop-img').removeAttribute('src');
        var modal = document.getElementById('modal-corte-imagem');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function confirmarCorte() {
        if (!cropper || !cropFileInput) return;

        var btn = document.getElementById('btn-confirmar-corte');
        btn.disabled = true;
        btn.innerHTML = 'Processando...';

        var cropData = cropper.getData(true);
        var originalFile = cropFileInput.files[0];
        var w = cropData.width, h = cropData.height;
        var maxDim = 1600;
        if (w > maxDim) { h *= maxDim / w; w = maxDim; }
        if (h > maxDim) { w *= maxDim / h; h = maxDim; }

        var croppedCanvas;
        try {
            croppedCanvas = cropper.getCroppedCanvas({
                width: Math.round(w),
                height: Math.round(h),
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
        } catch (e) {
            if (window.mostrarToast) window.mostrarToast('Erro ao processar corte', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle" style="font-size:16px"></i> Confirmar Corte';
            return;
        }

        croppedCanvas.toBlob(function (blob) {
            try {
                var name = (originalFile.name || 'imagem').replace(/\.[^.]+$/, '.jpg');
                var compressed = new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
                var dt = new DataTransfer();
                dt.items.add(compressed);
                cropFileInput.files = dt.files;

                var kb = Math.round(blob.size / 1024);
                if (window.mostrarToast) {
                    window.mostrarToast('Imagem cortada e comprimida: ' + kb + 'KB', 'info');
                }
            } catch (err) {
                console.warn('DataTransfer fallback', err);
            }
            previewImagem(cropFileInput);
            fecharModalCorte();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle" style="font-size:16px"></i> Confirmar Corte';
        }, 'image/jpeg', 0.85);
    }

    function comprimirDireto(input) {
        if (!input || !input.files || !input.files[0]) return;
        var file = input.files[0];
        if (file.size <= 1024 * 1024) {
            previewImagem(input);
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                var w = img.width, h = img.height;
                var maxDim = 1600;
                if (w > maxDim) { h *= maxDim / w; w = maxDim; }
                if (h > maxDim) { w *= maxDim / h; h = maxDim; }
                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);
                canvas.toBlob(function (blob) {
                    try {
                        var name = file.name.replace(/\.[^.]+$/, '.jpg');
                        var compressed = new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
                        var dt = new DataTransfer();
                        dt.items.add(compressed);
                        input.files = dt.files;
                        var kb = Math.round(blob.size / 1024);
                        if (window.mostrarToast) {
                            window.mostrarToast('Imagem comprimida de ' + Math.round(file.size / 1024) + 'KB para ' + kb + 'KB', 'info');
                        }
                    } catch (err) {}
                    previewImagem(input);
                }, 'image/jpeg', 0.85);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
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
