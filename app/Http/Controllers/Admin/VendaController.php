<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Cliente;
use App\Models\ItensVenda;
use App\Models\Produto;
use App\Models\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Vendas::with('cliente', 'itensVenda.produto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.vendas.index', compact('vendas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::approved()->with('estoque')->orderBy('nome')->get();

        return view('admin.vendas.create', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'itens' => 'required|array|min:1',
            'itens.*.id_produto' => 'required|exists:produtos,id_produto',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        try {
            $venda = DB::transaction(function () use ($validated) {
                $venda = Vendas::create([
                    'id_cliente' => $validated['id_cliente'],
                    'data_venda' => now(),
                    'valor_total' => 0,
                    'status_pagamento' => 'pending',
                ]);

                $total = 0;
                foreach ($validated['itens'] as $item) {
                    $produto = Produto::findOrFail($item['id_produto']);
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
            return back()->withErrors(['msg' => $e->getMessage()])->withInput();
        }

        ActivityLog::log('venda.criada', "Venda #{$venda->id_venda} criada por administrador.", $venda);

        return redirect()->route('admin.vendas.index')
            ->with('success', "Venda #{$venda->id_venda} registrada com sucesso!");
    }

    public function show(Vendas $venda)
    {
        $venda->load('cliente', 'itensVenda.produto');

        return view('admin.vendas.show', compact('venda'));
    }

    public function edit(Vendas $venda)
    {
        $venda->load('itensVenda.produto');
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::approved()->with('estoque')->orderBy('nome')->get();

        return view('admin.vendas.edit', compact('venda', 'clientes', 'produtos'));
    }

    public function update(Request $request, Vendas $venda)
    {
        if ($venda->status_pagamento === 'approved') {
            return back()->withErrors(['msg' => 'Não é possível alterar uma venda com pagamento aprovado.']);
        }

        $validated = $request->validate([
            'status_pagamento' => 'required|in:pending,approved,cancelled',
            'id_cliente' => 'required|exists:clientes,id_cliente',
        ]);

        $venda->update($validated);

        ActivityLog::log('venda.atualizada', "Venda #{$venda->id_venda} atualizada por administrador.", $venda);

        return redirect()->route('admin.vendas.index')
            ->with('success', "Venda #{$venda->id_venda} atualizada com sucesso!");
    }

    public function destroy(Vendas $venda)
    {
        if ($venda->status_pagamento === 'approved') {
            return back()->withErrors(['msg' => 'Não é possível excluir uma venda com pagamento aprovado.']);
        }

        $venda->delete();

        return redirect()->route('admin.vendas.index')
            ->with('success', "Venda #{$venda->id_venda} excluída com sucesso!");
    }
}
