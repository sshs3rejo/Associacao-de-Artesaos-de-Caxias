<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instrutores;
use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    public function index()
    {
        $oficinas = Oficina::with('instrutor')
            ->withCount('inscricoes')
            ->latest()
            ->paginate(15);

        return view('admin.oficinas.index', compact('oficinas'));
    }

    public function create()
    {
        $instrutores = Instrutores::orderBy('nome')->get();

        return view('admin.oficinas.create', compact('instrutores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'nullable|numeric',
            'id_instrutor' => 'required|exists:instrutores,id_instrutor',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'horario' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:255',
            'vagas' => 'nullable|integer|min:0',
        ]);

        Oficina::create($validated);

        return redirect()->route('admin.oficinas.index')
            ->with('success', 'Oficina criada com sucesso!');
    }

    public function edit(Oficina $oficina)
    {
        $instrutores = Instrutores::orderBy('nome')->get();

        return view('admin.oficinas.edit', compact('oficina', 'instrutores'));
    }

    public function update(Request $request, Oficina $oficina)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'nullable|numeric',
            'id_instrutor' => 'required|exists:instrutores,id_instrutor',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'horario' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:255',
            'vagas' => 'nullable|integer|min:0',
        ]);

        $oficina->update($validated);

        return redirect()->route('admin.oficinas.index')
            ->with('success', 'Oficina atualizada com sucesso!');
    }

    public function destroy(Oficina $oficina)
    {
        if ($oficina->inscricoes()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir esta oficina pois existem inscrições vinculadas a ela.']);
        }

        $oficina->delete();

        return back()->with('success', 'Oficina excluída com sucesso!');
    }
}
