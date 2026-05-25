@extends('layouts.main')

@section('titulo', 'Gestão de Categorias')

@section('content')
<div class="max-w-7xl mx-auto my-10 px-4 sm:px-6 lg:px-8">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel Administrativo', route('admin.dashboard')], ['Gestão de Categorias']]" />

    <div class="flex flex-col md:flex-row gap-8 mt-6">

        {{-- Left Column: Form (Create/Edit) --}}
        <div class="w-full md:w-1/3">
            <div id="form-card" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24 transition-all duration-300">
                {{-- Header --}}
                <div class="flex items-center gap-3 mb-1">
                    <div id="form-icon" class="w-9 h-9 rounded-xl bg-brand/10 flex items-center justify-center flex-shrink-0">
                        <x-icon name="plus" class="w-4 h-4 text-brand" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-brand leading-tight" id="card-title">Criar Nova Categoria</h2>
                        <p class="text-xs text-gray-400" id="card-subtitle">Cadastre categorias para os produtos</p>
                    </div>
                </div>

                {{-- Edit mode banner --}}
                <div id="edit-banner" class="hidden mt-3 mb-0 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2.5 flex items-center gap-2 text-yellow-800 text-xs font-semibold">
                    <x-icon name="pencil" class="w-3.5 h-3.5 text-yellow-600 flex-shrink-0" />
                    <span>Modo de edição ativo. Altere os dados e salve.</span>
                </div>

                <hr class="my-4 border-gray-100">

                <form id="form-categoria" action="{{ route('admin.categorias.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="input-nome" class="block font-bold mb-1 text-brand text-sm">Nome da Categoria <span class="text-red-400">*</span></label>
                        <input type="text" id="input-nome" name="nome_categoria"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none text-sm transition"
                            placeholder="Ex: Artesanato em Palha" required />
                    </div>

                    <div>
                        <label for="select-parent" class="block font-bold mb-1 text-brand text-sm">Categoria Pai <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <select id="select-parent" name="parent_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none text-sm transition">
                            <option value="">— Nenhuma (categoria raiz) —</option>
                            @foreach($parentCategorias as $cat)
                                <option value="{{ $cat->id_categoria }}">{{ $cat->nome_categoria }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Deixe vazio para criar uma categoria raiz. O sistema suporta apenas 2 níveis.</p>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="button" id="btn-cancelar" onclick="cancelarEdicao()"
                            class="hidden flex-1 py-3 border border-gray-300 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold text-sm transition cursor-pointer bg-white items-center justify-center gap-2">
                            <x-icon name="times" class="w-4 h-4" /> Cancelar
                        </button>
                        <button type="submit" id="btn-submit"
                            class="flex-1 py-3 bg-brand hover:bg-brand-light text-white rounded-xl font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center justify-center gap-2 text-sm">
                            <x-icon name="check-circle" class="w-4 h-4" />
                            <span id="btn-label">Salvar Categoria</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Categories Tree --}}
        <div class="w-full md:w-2/3">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-xl font-bold text-brand">Categorias Cadastradas</h2>
                    <span class="text-xs text-gray-400 font-semibold bg-gray-100 px-3 py-1 rounded-full">
                        {{ $categoriasTree->count() }} raiz / {{ $categoriasTree->sum(fn($c) => $c->children->count()) }} sub
                    </span>
                </div>
                <p class="text-xs text-gray-400 mb-6">Gerencie a árvore de categorias. Clique em <strong>Editar</strong> para modificar uma categoria.</p>

                @if($categoriasTree->isEmpty())
                    <div class="text-center py-14 text-gray-400 text-sm">
                        <x-icon name="tags" class="w-14 h-14 mx-auto text-gray-200 mb-3" />
                        <p class="font-semibold text-gray-500">Nenhuma categoria cadastrada</p>
                        <p class="text-xs mt-1">Use o formulário ao lado para adicionar a primeira categoria.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($categoriasTree as $parent)
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                {{-- Parent row --}}
                                <div class="flex items-center justify-between px-4 py-3 bg-brand/5 border-b border-gray-100">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <x-icon name="folder" class="w-4 h-4 text-brand flex-shrink-0" />
                                        <span class="font-bold text-brand truncate">{{ $parent->nome_categoria }}</span>
                                        <span class="text-[10px] font-semibold text-brand/60 bg-brand/10 px-2 py-0.5 rounded-full whitespace-nowrap">raiz</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                        <button type="button"
                                            onclick="editarCategoria({{ $parent->id_categoria }}, '{{ addslashes($parent->nome_categoria) }}', '')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold border border-yellow-400 text-yellow-700 rounded-lg hover:bg-yellow-50 cursor-pointer bg-white transition">
                                            <x-icon name="pencil" class="w-3 h-3" /> Editar
                                        </button>
                                        <form action="{{ route('admin.categorias.destroy', $parent->id_categoria) }}" method="POST" class="inline m-0"
                                            onsubmit="return confirm('Excluir a categoria \'{{ addslashes($parent->nome_categoria) }}\'?\n\nAtenção: se ela tiver subcategorias vinculadas, a exclusão será bloqueada.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold border border-red-300 text-red-600 rounded-lg hover:bg-red-50 cursor-pointer bg-white transition">
                                                <x-icon name="trash" class="w-3 h-3" /> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Children rows --}}
                                <div class="bg-white divide-y divide-gray-50">
                                    @forelse($parent->children as $child)
                                        <div class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 transition">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-gray-300 text-base ml-4 flex-shrink-0">↳</span>
                                                <span class="text-gray-700 text-sm truncate">{{ $child->nome_categoria }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                                <button type="button"
                                                    onclick="editarCategoria({{ $child->id_categoria }}, '{{ addslashes($child->nome_categoria) }}', '{{ $parent->id_categoria }}')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold border border-yellow-400 text-yellow-700 rounded-lg hover:bg-yellow-50 cursor-pointer bg-white transition">
                                                    <x-icon name="pencil" class="w-3 h-3" /> Editar
                                                </button>
                                                <form action="{{ route('admin.categorias.destroy', $child->id_categoria) }}" method="POST" class="inline m-0"
                                                    onsubmit="return confirm('Excluir a subcategoria \'{{ addslashes($child->nome_categoria) }}\'?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold border border-red-300 text-red-600 rounded-lg hover:bg-red-50 cursor-pointer bg-white transition">
                                                        <x-icon name="trash" class="w-3 h-3" /> Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-3 text-xs text-gray-400 italic flex items-center gap-2">
                                            <span class="ml-9">Nenhuma subcategoria vinculada a esta categoria.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    let modoEdicao = false;

    function editarCategoria(id, nome, parentId) {
        modoEdicao = true;

        // Update form action and method
        const form = document.getElementById('form-categoria');
        form.action = '/admin/categorias/' + id;

        let methodInput = document.getElementById('method-input');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.id = 'method-input';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        // Set field values
        document.getElementById('input-nome').value = nome;
        document.getElementById('select-parent').value = parentId;

        // Update UI to edit mode
        document.getElementById('card-title').textContent = 'Editando Categoria';
        document.getElementById('card-subtitle').textContent = 'Altere os dados e clique em Salvar';
        document.getElementById('btn-label').textContent = 'Salvar Alterações';
        document.getElementById('edit-banner').classList.remove('hidden');
        document.getElementById('edit-banner').classList.add('flex');
        document.getElementById('btn-cancelar').classList.remove('hidden');
        document.getElementById('btn-cancelar').classList.add('flex');

        // Highlight form card
        document.getElementById('form-card').classList.add('ring-2', 'ring-yellow-400', 'ring-offset-2');

        // Focus the name input
        document.getElementById('input-nome').focus();

        // Scroll to form
        document.getElementById('form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function cancelarEdicao() {
        modoEdicao = false;

        const form = document.getElementById('form-categoria');
        form.action = '{{ route("admin.categorias.store") }}';

        const methodInput = document.getElementById('method-input');
        if (methodInput) methodInput.remove();

        // Reset fields
        document.getElementById('input-nome').value = '';
        document.getElementById('select-parent').value = '';

        // Reset UI
        document.getElementById('card-title').textContent = 'Criar Nova Categoria';
        document.getElementById('card-subtitle').textContent = 'Cadastre categorias para os produtos';
        document.getElementById('btn-label').textContent = 'Salvar Categoria';
        document.getElementById('edit-banner').classList.add('hidden');
        document.getElementById('edit-banner').classList.remove('flex');
        document.getElementById('btn-cancelar').classList.add('hidden');
        document.getElementById('btn-cancelar').classList.remove('flex');
        document.getElementById('form-card').classList.remove('ring-2', 'ring-yellow-400', 'ring-offset-2');
    }
</script>
@endsection
