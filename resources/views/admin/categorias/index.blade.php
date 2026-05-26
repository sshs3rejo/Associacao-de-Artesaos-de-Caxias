@extends('layouts.main')
@section('titulo', 'Categorias de Produtos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Categorias']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Categorias de Produtos</h1>
    </div>

    @if($categorias->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-tags text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-lg">Nenhuma categoria cadastrada.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Categoria Pai</th>
                        <th class="p-3 text-center text-sm font-semibold">Subcategorias</th>
                        <th class="p-3 text-center text-sm font-semibold">Produtos</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categorias as $cat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $cat->nome_categoria }}</td>
                            <td class="p-3 text-sm">
                                @if($cat->parent)
                                    {{ $cat->parent->nome_categoria }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-center">{{ $cat->children_count }}</td>
                            <td class="p-3 text-sm text-center">{{ $cat->produtos_count }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.produtos.index', ['id_categoria' => $cat->id_categoria]) }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-blue-400 text-blue-600 hover:bg-blue-50 text-xs"
                                       title="Ver produtos desta categoria">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <button type="button"
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-xs cursor-pointer"
                                            title="Editar"
                                            onclick="editarCategoria({{ $cat->id_categoria }}, '{{ addslashes($cat->nome_categoria) }}', {{ $cat->parent_id ?? 'null' }})">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.categorias.destroy', $cat->id_categoria) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg font-semibold text-center no-underline border border-red-400 text-red-500 hover:bg-red-50 text-xs cursor-pointer"
                                                title="Excluir"
                                                onclick="var f=this.closest('form'); showConfirm('Excluir categoria {{ addslashes($cat->nome_categoria) }}?',function(){f.submit();});">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $categorias->links() }}
        </div>
    @endif
</div>

{{-- Modal Editar Categoria --}}
<div id="modal-editar-categoria" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="backdrop-filter: blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 relative">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 rounded-t-2xl">
            <h3 class="text-lg font-bold text-brand m-0 flex items-center gap-2">
                <i class="fas fa-edit" style="font-size:18px"></i>
                Editar Categoria
            </h3>
            <button type="button" onclick="fecharModalEditarCategoria()" class="text-gray-400 hover:text-gray-700 cursor-pointer border-0 bg-transparent flex items-center p-1">
                <i class="fas fa-times" style="font-size:20px"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="erro-modal-editar" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm hidden"></div>
            <div class="space-y-4">
                <div>
                    <label for="editar-categoria-nome" class="block font-bold mb-1 text-brand text-sm">Nome da Categoria</label>
                    <input type="text" id="editar-categoria-nome" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" />
                </div>
                <div>
                    <label for="editar-categoria-parent" class="block font-bold mb-1 text-brand text-sm">ID da Categoria Pai</label>
                    <input type="text" id="editar-categoria-parent" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" placeholder="Deixe em branco para categoria raiz" />
                    <p class="text-xs text-gray-400 mt-1">Informe o ID da categoria pai (opcional).</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="fecharModalEditarCategoria()" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-semibold text-sm transition cursor-pointer bg-white">
                    Cancelar
                </button>
                <button type="button" onclick="salvarEdicaoCategoria()" class="px-5 py-2.5 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center gap-2 text-sm" id="btn-editar-categoria">
                    <i class="fas fa-check-circle" style="font-size:16px"></i>
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let _editandoCategoriaId = null;

    function editarCategoria(id, nome, parentId) {
        _editandoCategoriaId = id;
        document.getElementById('editar-categoria-nome').value = nome;
        document.getElementById('editar-categoria-parent').value = parentId || '';
        document.getElementById('erro-modal-editar').classList.add('hidden');
        const modal = document.getElementById('modal-editar-categoria');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fecharModalEditarCategoria() {
        const modal = document.getElementById('modal-editar-categoria');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        _editandoCategoriaId = null;
    }

    function salvarEdicaoCategoria() {
        const nome = document.getElementById('editar-categoria-nome').value.trim();
        const parentId = document.getElementById('editar-categoria-parent').value;
        const erroDiv = document.getElementById('erro-modal-editar');
        const btn = document.getElementById('btn-editar-categoria');

        if (!nome) {
            erroDiv.textContent = 'Informe o nome da categoria.';
            erroDiv.classList.remove('hidden');
            return;
        }

        erroDiv.classList.add('hidden');
        btn.disabled = true;
        btn.textContent = 'Salvando...';

        fetch('/admin/categorias/' + _editandoCategoriaId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nome_categoria: nome, parent_id: parentId || null }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                erroDiv.textContent = data.message || 'Erro ao atualizar categoria.';
                erroDiv.classList.remove('hidden');
            }
        })
        .catch(() => {
            erroDiv.textContent = 'Erro de conexão. Tente novamente.';
            erroDiv.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Salvar';
        });
    }
</script>
@endsection