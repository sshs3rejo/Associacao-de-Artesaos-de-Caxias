<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InscricoesOficina;

class InscricaoOficinaController extends Controller
{
    public function index()
    {
        $inscricoes = InscricoesOficina::with('cliente', 'oficina')
            ->latest()
            ->paginate(20);

        return view('admin.inscricoes-oficina.index', compact('inscricoes'));
    }

    public function destroy(InscricoesOficina $inscricao)
    {
        $inscricao->delete();

        return back()->with('success', 'Inscrição cancelada com sucesso!');
    }
}
