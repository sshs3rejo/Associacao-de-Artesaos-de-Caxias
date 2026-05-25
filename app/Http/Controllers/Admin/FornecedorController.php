<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fornecedores;
use Illuminate\Http\Request;
use LaravelLegends\PtBrValidator\Rules\CelularComDdd;

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
            'telefone' => ['nullable', new CelularComDdd],
            'endereco' => 'nullable|string',
        ], [
            'telefone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
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
            'telefone' => ['nullable', new CelularComDdd],
            'endereco' => 'nullable|string',
        ], [
            'telefone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
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
