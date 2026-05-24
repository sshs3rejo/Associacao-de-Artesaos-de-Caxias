<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComprasMateriaPrima;
use App\Models\Fornecedores;
use App\Models\MateriasPrimas;
use Illuminate\Http\Request;

class CompraMateriaPrimaController extends Controller
{
    public function index()
    {
        $compras = ComprasMateriaPrima::with('fornecedor', 'materiaPrima')
            ->orderBy('data_compra', 'desc')
            ->orderBy('id_compra', 'desc')
            ->get();

        return view('admin.compras-materia-prima.index', compact('compras'));
    }

    public function create()
    {
        $fornecedores = Fornecedores::orderBy('nome')->get();
        $materiasPrimas = MateriasPrimas::orderBy('nome')->get();

        return view('admin.compras-materia-prima.create', compact('fornecedores', 'materiasPrimas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_fornecedor' => 'required|exists:_fornecedores,id_fornecedor',
            'id_materia' => 'required|exists:materias_primas,id_materia',
            'quantidade' => 'required|numeric|min:0',
            'preco_unitario' => 'required|numeric|min:0',
            'data_compra' => 'nullable|date',
        ]);

        $validated['valor_total'] = $validated['quantidade'] * $validated['preco_unitario'];

        ComprasMateriaPrima::create($validated);

        return redirect()->route('admin.compras-materia-prima.index')
            ->with('success', 'Compra cadastrada com sucesso!');
    }

    public function edit(ComprasMateriaPrima $compraMateriaPrima)
    {
        $fornecedores = Fornecedores::orderBy('nome')->get();
        $materiasPrimas = MateriasPrimas::orderBy('nome')->get();

        return view('admin.compras-materia-prima.edit', compact('compraMateriaPrima', 'fornecedores', 'materiasPrimas'));
    }

    public function update(Request $request, ComprasMateriaPrima $compraMateriaPrima)
    {
        $validated = $request->validate([
            'id_fornecedor' => 'required|exists:_fornecedores,id_fornecedor',
            'id_materia' => 'required|exists:materias_primas,id_materia',
            'quantidade' => 'required|numeric|min:0',
            'preco_unitario' => 'required|numeric|min:0',
            'data_compra' => 'nullable|date',
        ]);

        $validated['valor_total'] = $validated['quantidade'] * $validated['preco_unitario'];

        $compraMateriaPrima->update($validated);

        return redirect()->route('admin.compras-materia-prima.index')
            ->with('success', 'Compra atualizada com sucesso!');
    }

    public function destroy(ComprasMateriaPrima $compraMateriaPrima)
    {
        $compraMateriaPrima->delete();

        return back()->with('success', 'Compra excluída com sucesso!');
    }
}
