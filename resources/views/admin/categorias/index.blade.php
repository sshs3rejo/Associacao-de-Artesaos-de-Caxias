@extends('layouts.main')
@section('titulo', 'Categorias de Produtos')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Categorias']]" />
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl text-brand">Categorias de Produtos</h1>
        <a href="{{ route('admin.categorias.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-light text-white rounded-lg font-semibold text-sm transition no-underline">
            <x-icon name="plus" class="w-4 h-4" /> Nova Categoria
        </a>
    </div>

    @if($categorias->isEmpty())
        <div class="text-center py-5">
            <x-icon name="tags" class="w-12 h-12 text-gray-500 mb-3 mx-auto block" />
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
                    @foreach($categorias as $parent)
                        {{-- Categoria Pai --}}
                        <tr class="hover:bg-gray-50 transition-colors bg-gray-50/50">
                            <td class="font-semibold p-3 text-sm text-brand-dark flex items-center gap-1.5">
                                <x-icon name="folder" class="w-4 h-4 text-brand-light" />
                                {{ $parent->nome_categoria }}
                            </td>
                            <td class="p-3 text-sm text-gray-400 font-medium">— (Categoria Raiz)</td>
                            <td class="p-3 text-sm">
                                @if($parent->children->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand/10 text-brand-dark">
                                        {{ $parent->children->count() }} subcategorias
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-500">{{ $parent->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categorias.edit', $parent->id_categoria) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                        <x-icon name="pencil" class="w-4 h-4" /> Editar
                                    </a>
                                    <form action="{{ route('admin.categorias.destroy', $parent->id_categoria) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir a categoria {{ $parent->nome_categoria }}?',function(){f.submit();}); return false;">
                                            <x-icon name="trash" class="w-4 h-4" /> Remover
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Subcategorias --}}
                        @foreach($parent->children as $child)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 text-sm pl-8 text-gray-700 flex items-center gap-1.5">
                                    <span class="text-gray-300 font-light select-none">↳</span>
                                    <x-icon name="tag" class="w-3.5 h-3.5 text-gray-400" />
                                    {{ $child->nome_categoria }}
                                </td>
                                <td class="p-3 text-sm text-gray-500">
                                    <span class="inline-flex items-center gap-1 text-xs text-brand-light bg-brand/5 px-2 py-0.5 rounded font-semibold border border-brand/10">
                                        {{ $parent->nome_categoria }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-400">—</td>
                                <td class="p-3 text-sm text-gray-500">{{ $child->created_at->format('d/m/Y') }}</td>
                                <td class="p-3 text-sm text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categorias.edit', $child->id_categoria) }}" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm">
                                            <x-icon name="pencil" class="w-4 h-4" /> Editar
                                        </a>
                                        <form action="{{ route('admin.categorias.destroy', $child->id_categoria) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="inline-block px-3 py-1 rounded-lg font-semibold text-center no-underline border border-red-500 text-red-600 hover:bg-red-50 text-sm" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir a categoria {{ $child->nome_categoria }}?',function(){f.submit();}); return false;">
                                                <x-icon name="trash" class="w-4 h-4" /> Remover
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
