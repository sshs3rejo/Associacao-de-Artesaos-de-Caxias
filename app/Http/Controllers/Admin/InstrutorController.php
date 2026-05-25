<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instrutores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LaravelLegends\PtBrValidator\Rules\CelularComDdd;

class InstrutorController extends Controller
{
    public function index()
    {
        $instrutores = Instrutores::withCount('eventos', 'oficinas')
            ->orderBy('nome')
            ->get();

        return view('admin.instrutores.index', compact('instrutores'));
    }

    public function create()
    {
        return view('admin.instrutores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => ['nullable', new CelularComDdd],
            'email' => 'required|email|max:255|unique:instrutores,email',
            'especialidade' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'telefone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('instrutores', 'public');
        }

        Instrutores::create($validated);

        return redirect()->route('admin.instrutores.index')
            ->with('success', 'Instrutor cadastrado com sucesso!');
    }

    public function edit(Instrutores $instrutor)
    {
        return view('admin.instrutores.edit', compact('instrutor'));
    }

    public function update(Request $request, Instrutores $instrutor)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => ['nullable', new CelularComDdd],
            'email' => 'required|email|max:255|unique:instrutores,email,' . $instrutor->id_instrutor . ',id_instrutor',
            'especialidade' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'telefone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
        ]);

        if ($request->hasFile('foto')) {
            if ($instrutor->foto) {
                Storage::disk('public')->delete($instrutor->foto);
            }
            $validated['foto'] = $request->file('foto')->store('instrutores', 'public');
        }

        $instrutor->update($validated);

        return redirect()->route('admin.instrutores.index')
            ->with('success', 'Instrutor atualizado com sucesso!');
    }

    public function destroy(Instrutores $instrutor)
    {
        if ($instrutor->eventos()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir este instrutor pois existem eventos vinculados a ele.']);
        }

        if ($instrutor->oficinas()->count() > 0) {
            return back()->withErrors(['msg' => 'Não é possível excluir este instrutor pois existem oficinas vinculadas a ele.']);
        }

        if ($instrutor->foto) {
            Storage::disk('public')->delete($instrutor->foto);
        }

        $instrutor->delete();

        return back()->with('success', 'Instrutor excluído com sucesso!');
    }
}
