<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContatoRequest;
use App\Models\Contato;

class ContatoController extends Controller
{
    public function store(ContatoRequest $request)
    {
        $validated = $request->validated();

        Contato::create($validated);

        return back()->with('success', 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.');
    }
}
