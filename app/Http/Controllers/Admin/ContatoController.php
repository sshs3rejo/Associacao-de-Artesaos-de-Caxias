<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function index()
    {
        $contatos = Contato::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.contatos.index', compact('contatos'));
    }

    public function show(Contato $contato)
    {
        if (!$contato->lido) {
            $contato->update(['lido' => true]);
        }

        return view('admin.contatos.show', compact('contato'));
    }

    public function destroy(Contato $contato)
    {
        $contato->delete();

        return redirect()->route('admin.contatos.index')
            ->with('success', 'Mensagem excluída com sucesso!');
    }
}
