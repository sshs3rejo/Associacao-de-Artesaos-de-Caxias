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
        $categorias = CategoriasProdutos::withCount('produtos')
            ->orderBy('nome_categoria')
            ->paginate(15);

        return view('admin.categorias.index', compact('categorias'));
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'nome_categoria' => 'required|string|max:255',
        ]);

        $categoria = CategoriasProdutos::create($validated);

        Cache::forget('categorias_produtos');

        return response()->json([
            'success' => true,
            'categoria' => [
                'id_categoria' => $categoria->id_categoria,
                'nome_categoria' => $categoria->nome_categoria,
            ],
            'message' => 'Categoria criada com sucesso!',
        ]);
    }

    public function update(Request $request, CategoriasProdutos $categoria)
    {
        $validated = $request->validate([
            'nome_categoria' => 'required|string|max:255',
        ]);

        $categoria->update($validated);

        Cache::forget('categorias_produtos');

        return response()->json([
            'success' => true,
            'categoria' => [
                'id_categoria' => $categoria->id_categoria,
                'nome_categoria' => $categoria->nome_categoria,
            ],
            'message' => 'Categoria atualizada com sucesso!',
        ]);
    }

    public function destroy(CategoriasProdutos $categoria)
    {
        if ($categoria->produtos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir esta categoria pois existem produtos vinculados a ela.',
            ], 422);
        }

        $categoria->delete();

        Cache::forget('categorias_produtos');

        return response()->json([
            'success' => true,
            'message' => 'Categoria excluída com sucesso!',
        ]);
    }
}
