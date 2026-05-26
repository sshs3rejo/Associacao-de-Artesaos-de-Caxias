<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriasProdutos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriaProdutoController extends Controller
{
    public function quickStore(Request $request)
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

        $categoria = CategoriasProdutos::create($validated);

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_tree');
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

        return response()->json([
            'success' => true,
            'categoria' => [
                'id_categoria' => $categoria->id_categoria,
                'nome_categoria' => $categoria->nome_categoria,
                'parent_id' => $categoria->parent_id,
            ],
            'message' => 'Categoria criada com sucesso!',
        ]);
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
        Cache::forget('categorias_tree');
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

        return response()->json([
            'success' => true,
            'categoria' => [
                'id_categoria' => $categoria->id_categoria,
                'nome_categoria' => $categoria->nome_categoria,
                'parent_id' => $categoria->parent_id,
            ],
            'message' => 'Categoria atualizada com sucesso!',
        ]);
    }

    public function destroy(CategoriasProdutos $categoria)
    {
        if ($categoria->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir esta categoria pois ela possui subcategorias.',
            ], 422);
        }

        if ($categoria->produtos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir esta categoria pois existem produtos vinculados a ela.',
            ], 422);
        }

        $categoria->delete();

        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_tree');
        Cache::forget('categorias_hierarchical');
        Cache::forget('categorias_grouped_list');

        return response()->json([
            'success' => true,
            'message' => 'Categoria excluída com sucesso!',
        ]);
    }
}
