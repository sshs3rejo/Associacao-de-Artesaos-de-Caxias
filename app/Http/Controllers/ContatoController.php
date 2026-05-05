<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContatoController extends Controller
{
    /**
     * Processa o formulário de contato
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensagem' => 'required|string',
        ]);

        // Aqui você enviaria um e-mail ou salvaria no banco
        // Por enquanto, vamos apenas logar e simular sucesso
        Log::info('Contato recebido:', $validated);

        return back()->with('success', 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.');
    }
}
