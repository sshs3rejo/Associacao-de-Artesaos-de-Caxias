<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriasProdutos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriaProdutoController extends Controller
{
    public function index()
    {
        $categorias = CategoriasProdutos::with('parent', 'children')
            ->orderBy('nome_categoria')
            ->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        $parents = CategoriasProdutos::parents()
            ->orderBy('nome_categoria')
            ->get();

        return view('admin.categorias.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_categoria' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categorias_produtos,id_categoria',
        ]);

        CategoriasProdutos::create($validated);

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(CategoriasProdutos $categoria)
    {
        $parents = CategoriasProdutos::parents()
            ->where('id_categoria', '!=', $categoria->id_categoria)
            ->orderBy('nome_categoria')
            ->get();

        return view('admin.categorias.edit', compact('categoria', 'parents'));
    }

    public function update(Request $request, CategoriasProdutos $categoria)
    {
        $validated = $request->validate([
            'nome_categoria' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categorias_produtos,id_categoria',
        ]);

        $categoria->update($validated);

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(CategoriasProdutos $categoria)
    {
        if ($categoria->children()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir esta categoria pois ela possui subcategorias.']);
        }

        if ($categoria->produtos()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir esta categoria pois existem produtos vinculados a ela.']);
        }

        $categoria->delete();

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');

        return back()->with('success', 'Categoria excluída com sucesso!');
    }
}
