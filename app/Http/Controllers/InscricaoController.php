<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cliente;
use App\Models\Eventos;
use App\Models\InscricoesEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscricaoController extends Controller
{
    public function store(Request $request, Eventos $evento)
    {
        $user = $request->user();
        $cliente = Cliente::where('user_id', $user->id)->first();

        if (!$cliente) {
            return back()->withErrors(['msg' => 'Cliente não encontrado. Complete seu cadastro primeiro.']);
        }

        if ($evento->isLotado()) {
            return back()->withErrors(['msg' => 'Este evento está lotado.']);
        }

        if (!$evento->isAtivo()) {
            return back()->withErrors(['msg' => 'Este evento não está disponível para inscrição.']);
        }

        $jaInscrito = InscricoesEvento::where('id_cliente', $cliente->id_cliente)
            ->where('id_evento', $evento->id_evento)
            ->exists();

        if ($jaInscrito) {
            return back()->withErrors(['msg' => 'Você já está inscrito neste evento.']);
        }

        DB::transaction(function () use ($cliente, $evento) {
            InscricoesEvento::create([
                'id_cliente' => $cliente->id_cliente,
                'id_evento' => $evento->id_evento,
                'data_inscricao' => now(),
                'status_pagamento' => $evento->isGratuito() ? 'pago' : 'pendente',
            ]);

            $evento->decrementarVagas();
        });

        ActivityLog::log('evento.inscrito', "Inscrição no evento \"{$evento->nome}\" realizada.", $evento);

        return back()->with('success', 'Inscrição realizada com sucesso!');
    }

    public function destroy(Request $request, Eventos $evento)
    {
        $user = $request->user();
        $cliente = Cliente::where('user_id', $user->id)->first();

        if (!$cliente) {
            return back()->withErrors(['msg' => 'Cliente não encontrado.']);
        }

        $inscricao = InscricoesEvento::where('id_cliente', $cliente->id_cliente)
            ->where('id_evento', $evento->id_evento)
            ->first();

        if (! $inscricao) {
            return back()->withErrors(['msg' => 'Você não está inscrito neste evento.']);
        }

        DB::transaction(function () use ($inscricao, $evento) {
            $inscricao->delete();
            $evento->incrementarVagas();
        });

        ActivityLog::log('evento.inscricao.cancelada', "Inscrição no evento \"{$evento->nome}\" cancelada.", $evento);

        return back()->with('success', 'Inscrição cancelada com sucesso.');
    }
}
