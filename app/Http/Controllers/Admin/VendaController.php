<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendas;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Vendas::with('cliente', 'itensVenda.produto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.vendas.index', compact('vendas'));
    }

    public function show(Vendas $venda)
    {
        $venda->load('cliente', 'itensVenda.produto');

        return view('admin.vendas.show', compact('venda'));
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
