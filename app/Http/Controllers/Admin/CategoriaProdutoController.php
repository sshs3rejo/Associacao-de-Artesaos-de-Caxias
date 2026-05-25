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
        $categorias = CategoriasProdutos::parents()
            ->with(['children' => function ($q) {
                $q->orderBy('nome_categoria');
            }])
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
            'parent_id' => [
                'nullable',
                'exists:categorias_produtos,id_categoria',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = CategoriasProdutos::find($value);
                        if ($parent && $parent->parent_id !== null) {
                            $fail('A categoria pai selecionada é uma subcategoria. Não é permitido aninhamento com mais de um nível.');
                        }
                    }
                }
            ],
        ]);

        CategoriasProdutos::create($validated);

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

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
            'parent_id' => [
                'nullable',
                'exists:categorias_produtos,id_categoria',
                function ($attribute, $value, $fail) use ($categoria) {
                    if ($value) {
                        if ($value == $categoria->id_categoria) {
                            $fail('Uma categoria não pode ser pai de si mesma.');
                            return;
                        }

                        $parent = CategoriasProdutos::find($value);
                        if ($parent && $parent->parent_id !== null) {
                            $fail('A categoria pai selecionada é uma subcategoria. Não é permitido aninhamento com mais de um nível.');
                            return;
                        }

                        if ($categoria->children()->exists()) {
                            $fail('Esta categoria possui subcategorias e não pode se tornar uma subcategoria.');
                        }
                    }
                }
            ],
        ]);

        $categoria->update($validated);

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

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
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

        return back()->with('success', 'Categoria excluída com sucesso!');
    }
}
