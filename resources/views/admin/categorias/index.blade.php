@extends('layouts.main')
@section('titulo', 'Categorias de Produtos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Categorias']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Categorias de Produtos</h1>
        <a href="{{ route('admin.categorias.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <i class="fas fa-plus"></i> Nova Categoria
        </a>
    </div>

    @if($categorias->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-tags text-5xl text-gray-500 mb-3 block"></i>
            <p class="text-gray-500 text-lg">Nenhuma categoria cadastrada ainda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Nome</th>
                        <th class="p-3 text-left text-sm font-semibold">Categoria Pai</th>
                        <th class="p-3 text-left text-sm font-semibold">Subcategorias</th>
                        <th class="p-3 text-left text-sm font-semibold">Criada em</th>
                        <th class="p-3 text-right text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categorias as $categoria)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-semibold p-3 text-sm">{{ $categoria->nome_categoria }}</td>
                            <td class="p-3 text-sm text-gray-500">
                                {{ $categoria->parent?->nome_categoria ?? '<span class="text-gray-400">—</span>' }}
                            </td>
                            <td class="p-3 text-sm">
                                @if($categoria->children->count() > 0)
                                    <span class="text-gray-600">{{ $categoria->children->count() }} subcategorias</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ $categoria->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categorias.edit', $categoria->id_categoria) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                        <i class="fa fa-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.categorias.destroy', $categoria->id_categoria) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="return confirm('Tem certeza de que deseja excluir a categoria {{ $categoria->nome_categoria }}?')">
                                            <i class="fa fa-trash"></i> Remover
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
