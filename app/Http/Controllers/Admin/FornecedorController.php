<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fornecedores;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedores::withCount('materiasPrimas')
            ->orderBy('nome')
            ->get();

        return view('admin.fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('admin.fornecedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'contato' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        Fornecedores::create($validated);

        return redirect()->route('admin.fornecedores.index')
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Fornecedores $fornecedore)
    {
        return view('admin.fornecedores.edit', compact('fornecedore'));
    }

    public function update(Request $request, Fornecedores $fornecedore)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'contato' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        $fornecedore->update($validated);

        return redirect()->route('admin.fornecedores.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedores $fornecedore)
    {
        if ($fornecedore->materiasPrimas()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir este fornecedor pois existem matérias-primas vinculadas a ele.']);
        }

        $fornecedore->delete();

        return back()->with('success', 'Fornecedor excluído com sucesso!');
    }
}
