<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriasPrimas;
use App\Models\ComprasMateriaPrima;
use Illuminate\Http\Request;

class MateriaPrimaController extends Controller
{
    public function index()
    {
        $materiasPrimas = MateriasPrimas::withCount('fornecedores')
            ->orderBy('nome')
            ->get();

        return view('admin.materias-primas.index', compact('materiasPrimas'));
    }

    public function create()
    {
        return view('admin.materias-primas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|string|in:kg,g,L,mL,un,m,cm,par',
        ]);

        MateriasPrimas::create($validated);

        return redirect()->route('admin.materias-primas.index')
            ->with('success', 'Matéria-prima criada com sucesso!');
    }

    public function edit(MateriasPrimas $materiasPrima)
    {
        return view('admin.materias-primas.edit', compact('materiasPrima'));
    }

    public function update(Request $request, MateriasPrimas $materiasPrima)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|string|in:kg,g,L,mL,un,m,cm,par',
        ]);

        $materiasPrima->update($validated);

        return redirect()->route('admin.materias-primas.index')
            ->with('success', 'Matéria-prima atualizada com sucesso!');
    }

    public function destroy(MateriasPrimas $materiasPrima)
    {
        if ($materiasPrima->fornecedores()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir esta matéria-prima pois existem fornecedores vinculados a ela.']);
        }

        if (ComprasMateriaPrima::where('id_materia', $materiasPrima->id_materia)->exists()) {
            return back()->withErrors(['msg' => 'Não é possível excluir esta matéria-prima pois existem compras vinculadas a ela.']);
        }

        $materiasPrima->delete();

        return redirect()->route('admin.materias-primas.index')
            ->with('success', 'Matéria-prima excluída com sucesso!');
    }
}
