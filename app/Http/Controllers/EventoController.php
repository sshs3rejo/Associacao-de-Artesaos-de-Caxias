<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\InscricoesEvento;
use App\Models\Instrutores;
use Illuminate\Http\Request;
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
            $inscricoes = InscricoesEvento::where('id_cliente', auth()->id())
                ->with('evento')->get();
            $eventosPropostos = Eventos::where('id_artesan', auth()->id())
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
            $jaInscrito = InscricoesEvento::where('id_cliente', auth()->id())
                ->where('id_evento', $evento->id_evento)
                ->exists();
        }

        return view('eventodetalhes', compact('evento', 'jaInscrito'));
    }

    public function create()
    {
        $instrutores = Instrutores::all();

        return view('eventos.create', compact('instrutores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_evento' => 'required|in:feira,exposicao,workshop,lancamento,palestra,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'capacidade_maxima' => 'required|integer|min:0',
            'valor_inscricao' => 'required|numeric|min:0',
            'status' => 'required|in:planejado,confirmado,em_andamento,concluido,cancelado',
            'nome_instrutor' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
        unset($validated['nome_instrutor']);

        $validated['vagas_disponiveis'] = $validated['capacidade_maxima'];

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        $validated['is_approved'] = true;

        Eventos::create($validated);

        return redirect()->route('evento')->with('success', 'Evento criado com sucesso!');
    }

    public function edit($id)
    {
        $evento = Eventos::with('instrutor')->findOrFail($id);
        $instrutores = Instrutores::all();

        return view('eventos.edit', compact('evento', 'instrutores'));
    }

    public function update(Request $request, $id)
    {
        $evento = Eventos::with('instrutor')->findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_evento' => 'required|in:feira,exposicao,workshop,lancamento,palestra,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'capacidade_maxima' => 'required|integer|min:0',
            'valor_inscricao' => 'required|numeric|min:0',
            'status' => 'required|in:planejado,confirmado,em_andamento,concluido,cancelado',
            'nome_instrutor' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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

        return redirect()->route('evento')->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $evento = Eventos::findOrFail($id);

        if ($evento->imagem) {
            Storage::disk('public')->delete($evento->imagem);
        }

        $evento->inscricoes()->delete();

        $evento->delete();

        return redirect()->route('evento')->with('success', 'Evento removido com sucesso!');
    }
}