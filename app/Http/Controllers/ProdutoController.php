<?php

namespace App\Http\Controllers;

use App\Models\CategoriasProdutos;
use App\Models\Cliente;
use App\Models\ItensVenda;
use App\Models\Produto;
use App\Models\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $produtos = Produto::with(['categoria', 'estoque', 'artisan'])
                ->orderBy('id_produto', 'desc')->paginate(15);
            return view('produtos', compact('produtos'));
        }

        if (auth()->check() && auth()->user()->isArtisan()) {
            $produtos = Produto::where('id_artesan', auth()->id())
                ->with('categoria', 'estoque')->get();
            return view('produtos', compact('produtos'));
        }

        $categorias = CategoriasProdutos::getAllCached();

        $query = Produto::approved()->with(['categoria', 'estoque', 'artisan.artisanProfile']);

        if ($request->has('categoria') && $request->categoria != '') {
            $categoriaId = $request->categoria;
            $subCategoryIds = CategoriasProdutos::where('parent_id', $categoriaId)->pluck('id_categoria')->toArray();
            $categoryIds = array_merge([$categoriaId], $subCategoryIds);

            $query->whereIn('id_categoria', $categoryIds);
        }

        if ($request->has('busca') && $request->busca != '') {
            $termo = '%' . $request->busca . '%';
            $query->where(function($q) use ($termo) {
                $q->where('nome', 'like', $termo)
                  ->orWhere('descricao', 'like', $termo);
            });
        }

        $produtos = $query->paginate(12)->withQueryString();

        return view('produtos', [
            'produtos' => $produtos,
            'categorias' => $categorias,
        ]);
    }

    public function create()
    {
        $categorias = CategoriasProdutos::getAllCached();

        return view('produtos.create', compact('categorias'));
    }

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

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $validated['is_approved'] = true;

        $produto = Produto::create($validated);

        $produto->estoque()->create([
            'id_produto' => $produto->id_produto,
            'quantidade' => $request->quantidade,
        ]);

        return redirect()->route('produtos')->with('success', 'Produto criado com sucesso!');
    }

    public function edit($id)
    {
        $produto = Produto::with(['categoria', 'estoque'])->findOrFail($id);
        $categorias = CategoriasProdutos::getAllCached();

        return view('produtos.edit', compact('produto', 'categorias'));
    }

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

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        } elseif ($request->boolean('remover_imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = null;
        }

        $produto->update($validated);

        $produto->estoque()->updateOrCreate(
            ['id_produto' => $produto->id_produto],
            ['quantidade' => $request->quantidade]
        );

        return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }

        $produto->estoque()->delete();

        $produto->delete();

        return redirect()->route('produtos')->with('success', 'Produto removido com sucesso!');
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cliente = Cliente::where('user_id', $user->id)->first();

        if (!$cliente) {
            return response()->json(['error' => 'Cliente não encontrado. Complete seu cadastro primeiro.'], 400);
        }

        $validated = $request->validate([
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'required|exists:produto,id_produto',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        try {
            $venda = DB::transaction(function () use ($validated, $cliente) {
                $venda = Vendas::create([
                    'id_cliente' => $cliente->id_cliente,
                    'data_venda' => now(),
                    'valor_total' => 0,
                    'status_pagamento' => 'pending',
                ]);

                $total = 0;
                foreach ($validated['itens'] as $item) {
                    $produto = Produto::findOrFail($item['id']);

                    $estoque = $produto->estoque;
                    if (!$estoque || $estoque->quantidade < $item['quantidade']) {
                        throw new \RuntimeException("Estoque insuficiente para \"{$produto->nome}\".");
                    }

                    $subtotal = $produto->preco * $item['quantidade'];
                    $total += $subtotal;

                    ItensVenda::create([
                        'id_venda' => $venda->id_venda,
                        'id_produto' => $produto->id_produto,
                        'quantidade' => $item['quantidade'],
                        'preco_unitario' => $produto->preco,
                    ]);

                    $estoque->decrement('quantidade', $item['quantidade']);
                }

                $venda->update(['valor_total' => $total]);

                return $venda;
            });
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erro ao processar pedido.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pedido #' . $venda->id_venda . ' realizado com sucesso!',
        ]);
    }
}