<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('user')
            ->withCount('vendas')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('user', 'vendas.itensVenda.produto');

        return view('admin.clientes.show', compact('cliente'));
    }
}
