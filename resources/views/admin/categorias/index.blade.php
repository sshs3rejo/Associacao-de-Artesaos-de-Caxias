@extends('layouts.main')
@section('titulo', 'Categorias de Produtos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Categorias']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Categorias de Produtos</h1>
        <button type="button" onclick="criarCategoria()" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold shadow-sm transition no-underline cursor-pointer border-0">
            <i class="fas fa-plus" style="font-size:14px"></i> Nova Categoria
        </button>
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

{{-- Modal Categoria (criar/editar) --}}
<div id="modal-categoria" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="backdrop-filter: blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 relative">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 rounded-t-2xl">
            <h3 class="text-lg font-bold text-brand m-0 flex items-center gap-2" id="modal-categoria-titulo">
                <i class="fas fa-plus" style="font-size:18px"></i>
                Criar Categoria
            </h3>
            <button type="button" onclick="fecharModalCategoria()" class="text-gray-400 hover:text-gray-700 cursor-pointer border-0 bg-transparent flex items-center p-1">
                <i class="fas fa-times" style="font-size:20px"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="erro-modal-categoria" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm hidden"></div>
            <div class="space-y-4">
                <div>
                    <label for="modal-categoria-nome" class="block font-bold mb-1 text-brand text-sm">Nome da Categoria</label>
                    <input type="text" id="modal-categoria-nome" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" />
                </div>
                <div>
                    <label for="modal-categoria-parent" class="block font-bold mb-1 text-brand text-sm">ID da Categoria Pai</label>
                    <input type="text" id="modal-categoria-parent" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-brand-light focus:ring-1 focus:ring-brand-light outline-none text-sm" placeholder="Deixe em branco para categoria raiz" />
                    <p class="text-xs text-gray-400 mt-1" id="modal-categoria-parent-hint">Informe o ID da categoria pai (opcional).</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="fecharModalCategoria()" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-semibold text-sm transition cursor-pointer bg-white">
                    Cancelar
                </button>
                <button type="button" onclick="salvarCategoria()" class="px-5 py-2.5 bg-brand hover:bg-brand-light text-white rounded-lg font-bold shadow-sm transition duration-200 cursor-pointer border-0 flex items-center gap-2 text-sm" id="btn-salvar-categoria">
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
let _categoriaEditId = null;

function abrirModalCategoria() {
    document.getElementById('modal-categoria-nome').value = '';
    document.getElementById('modal-categoria-parent').value = '';
    document.getElementById('erro-modal-categoria').classList.add('hidden');
    const modal = document.getElementById('modal-categoria');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function fecharModalCategoria() {
    document.getElementById('modal-categoria').classList.add('hidden');
    document.getElementById('modal-categoria').classList.remove('flex');
    _categoriaEditId = null;
}

function criarCategoria() {
    _categoriaEditId = null;
    document.getElementById('modal-categoria-titulo').innerHTML = '<i class="fas fa-plus" style="font-size:18px"></i> Criar Categoria';
    document.getElementById('btn-salvar-categoria').innerHTML = '<i class="fas fa-check-circle" style="font-size:16px"></i> Salvar';
    abrirModalCategoria();
}

function editarCategoria(id, nome, parentId) {
    _categoriaEditId = id;
    document.getElementById('modal-categoria-titulo').innerHTML = '<i class="fas fa-edit" style="font-size:18px"></i> Editar Categoria';
    document.getElementById('btn-salvar-categoria').innerHTML = '<i class="fas fa-check-circle" style="font-size:16px"></i> Salvar';
    document.getElementById('modal-categoria-nome').value = nome;
    document.getElementById('modal-categoria-parent').value = parentId || '';
    abrirModalCategoria();
}

function salvarCategoria() {
    const nome = document.getElementById('modal-categoria-nome').value.trim();
    const parentId = document.getElementById('modal-categoria-parent').value;
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

    const url = _categoriaEditId
        ? '/admin/categorias/' + _categoriaEditId
        : window._quickStoreUrl || '/admin/categorias/quick-store';

    const method = _categoriaEditId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
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
            erroDiv.textContent = data.message || 'Erro ao salvar categoria.';
            erroDiv.classList.remove('hidden');
        }
    })
    .catch(function(err) {
        erroDiv.textContent = 'Erro ao salvar categoria. Veja o console (F12).';
        erroDiv.classList.remove('hidden');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Salvar';
    });
}
</script>
<script>
window._quickStoreUrl = '{{ route("admin.categorias.quick-store") }}';
</script>
@endsection