<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\InscricoesEvento;
use Illuminate\Http\Request;

class InscricaoController extends Controller
{
    public function store(Request $request, Eventos $evento)
    {
        $user = $request->user();

        if ($evento->isLotado()) {
            return back()->withErrors(['msg' => 'Este evento está lotado.']);
        }

        if (!$evento->isAtivo()) {
            return back()->withErrors(['msg' => 'Este evento não está disponível para inscrição.']);
        }

        $jaInscrito = InscricoesEvento::where('id_cliente', $user->id)
            ->where('id_evento', $evento->id_evento)
            ->exists();

        if ($jaInscrito) {
            return back()->withErrors(['msg' => 'Você já está inscrito neste evento.']);
        }

        InscricoesEvento::create([
            'id_cliente' => $user->id,
            'id_evento' => $evento->id_evento,
            'data_inscricao' => now(),
            'status_pagamento' => $evento->isGratuito() ? 'pago' : 'pendente',
        ]);

        $evento->decrementarVagas();

        return back()->with('success', 'Inscrição realizada com sucesso!');
    }

    public function destroy(Request $request, Eventos $evento)
    {
        $user = $request->user();

        $inscricao = InscricoesEvento::where('id_cliente', $user->id)
            ->where('id_evento', $evento->id_evento)
            ->first();

        if (! $inscricao) {
            return back()->withErrors(['msg' => 'Você não está inscrito neste evento.']);
        }

        $inscricao->delete();
        $evento->incrementarVagas();

        return back()->with('success', 'Inscrição cancelada com sucesso.');
    }
}
