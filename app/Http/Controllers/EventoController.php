<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoRequest;
use App\Models\ActivityLog;
use App\Models\Cliente;
use App\Models\Eventos;
use App\Models\InscricoesEvento;
use App\Models\Instrutores;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $eventos = Eventos::with(['artisan', 'instrutor'])
                ->orderBy('id_evento', 'desc')->paginate(15);
            return view('eventos', compact('eventos'));
        }

        if (auth()->check() && auth()->user()->isArtisan()) {
            $cliente = Cliente::where('user_id', auth()->id())->first();
            $inscricoes = collect();
            if ($cliente) {
                $inscricoes = InscricoesEvento::where('id_cliente', $cliente->id_cliente)
                    ->with('evento')->get();
            }
            $eventosPropostos = Eventos::where('id_artesan', auth()->id())
                ->with('instrutor')
                ->orderBy('id_evento', 'desc')->get();
            return view('eventos', compact('inscricoes', 'eventosPropostos'));
        }

        $eventos = Cache::remember('eventos_publicos', 300, function () {
            return Eventos::approved()->where('status', '!=', 'cancelado')
                ->where('data_inicio', '>=', now())
                ->orderBy('data_inicio', 'asc')
                ->get();
        });

        return view('eventos', ['eventos' => $eventos]);
    }

    public function show($id)
    {
        $evento = Eventos::with('instrutor')->findOrFail($id);

        $jaInscrito = false;
        if (auth()->check()) {
            $cliente = Cliente::where('user_id', auth()->id())->first();
            if ($cliente) {
                $jaInscrito = InscricoesEvento::where('id_cliente', $cliente->id_cliente)
                    ->where('id_evento', $evento->id_evento)
                    ->exists();
            }
        }

        return view('eventodetalhes', compact('evento', 'jaInscrito'));
    }

    public function create()
    {
        $instrutores = Instrutores::all();
        $isArtisan = request()->routeIs('artesan.*');
        $evento = new Eventos();

        return view('eventos.form', compact('instrutores', 'isArtisan', 'evento'));
    }

    public function store(EventoRequest $request)
    {
        $validated = $request->validated();
        $isArtisan = request()->routeIs('artesan.*');

        if ($isArtisan) {
            $validated['id_artesan'] = auth()->id();
            $validated['is_approved'] = false;
            $validated['status'] = 'planejado';
        } else {
            $validated['is_approved'] = true;
            if (!empty($validated['nome_instrutor'])) {
                $instrutor = Instrutores::firstOrCreate(
                    ['nome' => $validated['nome_instrutor']],
                    [
                        'email' => 'pendente_' . uniqid() . '@associacao.com.br',
                        'especialidade' => 'Não definida'
                    ]
                );
                $validated['id_instrutor'] = $instrutor->id_instrutor;
            }
        }
        unset($validated['nome_instrutor']);

        $validated['vagas_disponiveis'] = $validated['capacidade_maxima'];

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        $evento = Eventos::create($validated);

        Cache::forget('eventos_publicos');

        if ($isArtisan) {
            ActivityLog::log('evento.proposto', "Evento \"{$evento->nome}\" proposto por " . auth()->user()->name . ".", $evento);
            return redirect()->route('evento')->with('success', 'Proposta de evento enviada com sucesso! Aguarde a aprovação do administrador.');
        }

        ActivityLog::log('evento.criado', "Evento \"{$evento->nome}\" criado.", $evento);

        return redirect()->route('evento')->with('success', 'Evento criado com sucesso!');
    }

    public function edit($id)
    {
        $evento = Eventos::with('instrutor')->findOrFail($id);
        $isArtisan = request()->routeIs('artesan.*');

        if ($isArtisan && $evento->id_artesan !== auth()->id()) {
            abort(403);
        }

        $instrutores = Instrutores::all();

        return view('eventos.form', compact('evento', 'instrutores', 'isArtisan'));
    }

    public function update(EventoRequest $request, $id)
    {
        $evento = Eventos::with('instrutor')->findOrFail($id);
        $isArtisan = request()->routeIs('artesan.*');

        if ($isArtisan && $evento->id_artesan !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($isArtisan) {
            unset($validated['nome_instrutor']);
            unset($validated['status']);
        } else {
            if (!empty($validated['nome_instrutor'])) {
                $instrutor = Instrutores::firstOrCreate(
                    ['nome' => $validated['nome_instrutor']],
                    [
                        'email' => 'pendente_' . uniqid() . '@associacao.com.br',
                        'especialidade' => 'Não definida'
                    ]
                );
                $validated['id_instrutor'] = $instrutor->id_instrutor;
            } else {
                $validated['id_instrutor'] = null;
            }
            unset($validated['nome_instrutor']);
        }

        if ($validated['capacidade_maxima'] != $evento->capacidade_maxima) {
            $diferenca = $validated['capacidade_maxima'] - $evento->capacidade_maxima;
            $validated['vagas_disponiveis'] = $evento->vagas_disponiveis + $diferenca;
        }

        if ($request->hasFile('imagem')) {
            if ($evento->imagem) {
                Storage::disk('public')->delete($evento->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        $evento->update($validated);

        Cache::forget('eventos_publicos');

        if ($isArtisan) {
            ActivityLog::log('evento.atualizado', "Evento \"{$evento->nome}\" atualizado por artesão.", $evento);
        } else {
            ActivityLog::log('evento.atualizado', "Evento \"{$evento->nome}\" atualizado.", $evento);
        }

        return redirect()->route('evento')->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $evento = Eventos::findOrFail($id);
        $isArtisan = request()->routeIs('artesan.*');

        if ($isArtisan && $evento->id_artesan !== auth()->id()) {
            abort(403);
        }

        if ($evento->imagem) {
            Storage::disk('public')->delete($evento->imagem);
        }

        Cache::forget('eventos_publicos');

        if ($isArtisan) {
            ActivityLog::log('evento.removido', "Evento \"{$evento->nome}\" removido por artesão.", $evento);
        } else {
            ActivityLog::log('evento.removido', "Evento \"{$evento->nome}\" removido.", $evento);
        }

        $evento->inscricoes()->delete();

        $evento->delete();

        return redirect()->route('evento')->with('success', 'Evento removido com sucesso!');
    }
}