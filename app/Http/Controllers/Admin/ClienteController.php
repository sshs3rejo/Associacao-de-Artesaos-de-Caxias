<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $cliente = Cliente::create($validated);

        ActivityLog::log('cliente.criado', "Cliente {$cliente->nome} criado por administrador.", $cliente);

        return redirect()->route('admin.clientes.index')
            ->with('success', "Cliente {$cliente->nome} cadastrado com sucesso!");
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('user', 'vendas.itensVenda.produto');

        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $cliente->update($validated);

        ActivityLog::log('cliente.atualizado', "Cliente {$cliente->nome} atualizado por administrador.", $cliente);

        return redirect()->route('admin.clientes.index')
            ->with('success', "Cliente {$cliente->nome} atualizado com sucesso!");
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->vendas()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir cliente com vendas vinculadas.']);
        }

        $cliente->delete();

        return back()->with('success', "Cliente {$cliente->nome} excluído com sucesso!");
    }
}
