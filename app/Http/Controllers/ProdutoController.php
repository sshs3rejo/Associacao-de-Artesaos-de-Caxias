<?php

namespace App\Http\Controllers;

use App\Models\CategoriasProdutos;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Exibe a lista de produtos (público)
     */
    public function index(Request $request)
    {
        $categorias = CategoriasProdutos::all();

        $query = Produto::query();

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('id_categoria', $request->categoria);
        }

        if ($request->has('busca') && $request->busca != '') {
            $termo = '%' . $request->busca . '%';
            $query->where(function($q) use ($termo) {
                $q->where('nome', 'like', $termo)
                  ->orWhere('descricao', 'like', $termo);
            });
        }

        $produtos = $query->get();

        return view('produtos', [
            'produtos' => $produtos,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Exibe o formulário de criação de produto (admin)
     */
    public function create()
    {
        $categorias = CategoriasProdutos::all();

        return view('produtos.create', compact('categorias'));
    }

    /**
     * Salva um novo produto (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantidade' => 'required|integer|min:0',
        ]);

        // Upload da imagem se fornecida
        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $produto = Produto::create($validated);

        // Cria o registro no estoque
        $produto->estoque()->create([
            'id_produto' => $produto->id_produto,
            'quantidade' => $request->quantidade,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de produto (admin)
     */
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        $categorias = CategoriasProdutos::all();

        return view('produtos.edit', compact('produto', 'categorias'));
    }

    /**
     * Atualiza um produto existente (admin)
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantidade' => 'required|integer|min:0',
        ]);

        // Lógica de remoção/substituição de imagem
        if ($request->hasFile('imagem')) {
            // Se enviou uma nova imagem, deleta a antiga e salva a nova
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        } elseif ($request->boolean('remover_imagem')) {
            // Se marcou para remover e não enviou uma nova
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = null;
        }

        $produto->update($validated);

        // Atualiza a quantidade no estoque
        $produto->estoque()->updateOrCreate(
            ['id_produto' => $produto->id_produto],
            ['quantidade' => $request->quantidade]
        );

        return redirect()->route('admin.dashboard')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um produto (admin)
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }

        $produto->estoque()->delete();

        $produto->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produto removido com sucesso!');
    }
}
