<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\Instrutores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    /**
     * Exibe a lista de eventos (público)
     */
    public function index()
    {
        $eventos = Eventos::approved()->where('status', '!=', 'cancelado')
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio', 'asc')
            ->get();

        return view('eventos', ['eventos' => $eventos]);
    }

    /**
     * Exibe detalhes de um evento específico (público)
     */
    public function show($id)
    {
        $evento = Eventos::with('instrutor')->find($id);

        $jaInscrito = false;
        if (auth()->check()) {
            $jaInscrito = \App\Models\InscricoesEvento::where('id_cliente', auth()->id())
                ->where('id_evento', $evento->id_evento)
                ->exists();
        }

        return view('eventodetalhes', compact('evento', 'jaInscrito'));
    }

    /**
     * Exibe o formulário de criação de evento (admin)
     */
    public function create()
    {
        $instrutores = Instrutores::all();

        return view('eventos.create', compact('instrutores'));
    }

    /**
     * Salva um novo evento (admin)
     */
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

        // Processamento do instrutor
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

        // Define vagas disponíveis igual à capacidade máxima
        $validated['vagas_disponiveis'] = $validated['capacidade_maxima'];

        // Upload da imagem se fornecida
        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        Eventos::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de evento (admin)
     */
    public function edit($id)
    {
        $evento = Eventos::findOrFail($id);
        $instrutores = Instrutores::all();

        return view('eventos.edit', compact('evento', 'instrutores'));
    }

    /**
     * Atualiza um evento existente (admin)
     */
    public function update(Request $request, $id)
    {
        $evento = Eventos::findOrFail($id);

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

        // Processamento do instrutor
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

        // Atualiza vagas disponíveis se a capacidade máxima mudou
        if ($validated['capacidade_maxima'] != $evento->capacidade_maxima) {
            $diferenca = $validated['capacidade_maxima'] - $evento->capacidade_maxima;
            $validated['vagas_disponiveis'] = $evento->vagas_disponiveis + $diferenca;
        }

        // Upload da nova imagem se fornecida
        if ($request->hasFile('imagem')) {
            // Deleta a imagem antiga se existir
            if ($evento->imagem) {
                Storage::disk('public')->delete($evento->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        $evento->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove um evento (admin)
     */
    public function destroy($id)
    {
        $evento = Eventos::findOrFail($id);

        if ($evento->imagem) {
            Storage::disk('public')->delete($evento->imagem);
        }

        $evento->inscricoes()->delete();

        $evento->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Evento removido com sucesso!');
    }
}
