<?php

namespace App\Http\Controllers;

use App\Models\ArtisanProfile;
use App\Models\CategoriasProdutos;
use App\Models\Cliente;
use App\Models\Eventos;
use App\Models\InscricoesEvento;
use App\Models\ItensVenda;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtisanController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $totalProdutos = Produto::where('id_artesan', $user->id)->count();
        $eventosInscritos = InscricoesEvento::where('id_cliente', $user->id)->count();
        $perfil = $user->artisanProfile;

        // Vendas específicas dos produtos deste artesão
        $minhasVendas = ItensVenda::whereHas('produto', function ($query) use ($user) {
            $query->where('id_artesan', $user->id);
        })->with(['venda.cliente', 'produto'])->orderBy('id_item', 'desc')->paginate(10);

        return view('artesan.dashboard', compact('user', 'totalProdutos', 'eventosInscritos', 'perfil', 'minhasVendas'));
    }

    public function perfil()
    {
        $user = auth()->user();
        $perfil = $user->artisanProfile ?? new ArtisanProfile(['user_id' => $user->id]);
        return view('artesan.perfil', compact('user', 'perfil'));
    }

    public function atualizarPerfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialty' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'is_public' => ['nullable', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update(['name' => $validated['name']]);

        $profile = $user->artisanProfile ?? ArtisanProfile::create(['user_id' => $user->id]);

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('artesao-fotos', 'public');
        }

        $profile->update([
            'phone' => $validated['phone'] ?? $profile->phone,
            'specialty' => $validated['specialty'] ?? $profile->specialty,
            'bio' => $validated['bio'] ?? $profile->bio,
            'instagram' => $validated['instagram'] ?? $profile->instagram,
            'facebook' => $validated['facebook'] ?? $profile->facebook,
            'whatsapp' => $validated['whatsapp'] ?? $profile->whatsapp,
            'is_public' => $request->boolean('is_public'),
            'profile_photo' => $validated['profile_photo'] ?? $profile->profile_photo,
        ]);

        return redirect()->route('artesan.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function userProfile()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isArtisan()) {
            $profile = $user->artisanProfile;
            if ($profile && $profile->isApproved()) {
                return redirect()->route('artesan.dashboard');
            }
            return view('profile.user', compact('user', 'profile'));
        }

        $profile = null;
        return view('profile.user', compact('user', 'profile'));
    }

    public function tornarSePeloPerfil(Request $request)
    {
        $user = auth()->user();

        if ($user->isArtisan()) {
            return back()->withErrors(['msg' => 'Você já é um artesão.']);
        }

        $validated = $request->validate([
            'cpf' => ['required', 'string', 'max:14'],
            'telefone' => ['required', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $data = [
            'user_id' => $user->id,
            'cpf' => $validated['cpf'],
            'phone' => $validated['telefone'],
            'bio' => $validated['bio'] ?? null,
            'approved_at' => null,
        ];

        if ($request->hasFile('foto')) {
            $data['profile_photo'] = $request->file('foto')->store('artesao-fotos', 'public');
        }

        ArtisanProfile::create($data);

        $user->update(['role' => 'artisan']);

        Cliente::where('user_id', $user->id)->update([
            'telefone' => $validated['telefone'],
        ]);

        return redirect()->route('user.perfil')
            ->with('success', 'Solicitação enviada! Aguarde a aprovação do administrador.');
    }

    public function publico(User $user)
    {
        $perfil = $user->artisanProfile;

        if (! $perfil || ! $perfil->is_public || ! $perfil->isApproved()) {
            abort(404);
        }

        $produtos = Produto::where('id_artesan', $user->id)->with('categoria')->get();
        return view('artesan.publico', compact('user', 'perfil', 'produtos'));
    }

    public function editarProduto(Produto $produto)
    {
        if ($produto->id_artesan !== auth()->id()) {
            abort(403);
        }
        $categorias = CategoriasProdutos::all();
        return view('artesan.produtos-edit', compact('produto', 'categorias'));
    }

    public function atualizarProduto(Request $request, Produto $produto)
    {
        if ($produto->id_artesan !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantidade' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        } elseif ($request->boolean('remover_imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = null;
        }

        $produto->update($validated);

        $produto->estoque()->updateOrCreate(
            ['id_produto' => $produto->id_produto],
            ['quantidade' => $request->quantidade]
        );

        return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso!');
    }

    public function deletarProduto(Produto $produto)
    {
        if ($produto->id_artesan !== auth()->id()) {
            abort(403);
        }

        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }

        $produto->estoque()->delete();
        $produto->delete();

        return redirect()->route('produtos')->with('success', 'Produto removido com sucesso!');
    }

    public function criarProduto()
    {
        $categorias = CategoriasProdutos::all();
        return view('artesan.produtos-create', compact('categorias'));
    }

    public function salvarProduto(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantidade' => 'required|integer|min:0',
        ]);

        $validated['id_artesan'] = $user->id;
        $validated['is_approved'] = false; // Aguardando aprovação!

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $produto = Produto::create($validated);

        // Cria o registro no estoque
        $produto->estoque()->create([
            'id_produto' => $produto->id_produto,
            'quantidade' => $request->quantidade,
        ]);

        return redirect()->route('produtos')->with('success', 'Produto proposto com sucesso! Aguarde a aprovação do administrador.');
    }

    public function criarEvento()
    {
        return view('artesan.eventos-create');
    }

    public function salvarEvento(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_evento' => 'required|in:feira,exposicao,workshop,lancamento,palestra,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'capacidade_maxima' => 'required|integer|min:1',
            'valor_inscricao' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['id_artesan'] = $user->id;
        $validated['is_approved'] = false; // Aguardando aprovação!
        $validated['status'] = 'planejado';
        $validated['vagas_disponiveis'] = $validated['capacidade_maxima'];

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        Eventos::create($validated);

        return redirect()->route('evento')->with('success', 'Proposta de evento enviada com sucesso! Aguarde a aprovação do administrador.');
    }

    public function editarEvento(Eventos $evento)
    {
        if ($evento->id_artesan !== auth()->id()) {
            abort(403);
        }
        return view('artesan.eventos-edit', compact('evento'));
    }

    public function atualizarEvento(Request $request, Eventos $evento)
    {
        if ($evento->id_artesan !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_evento' => 'required|in:feira,exposicao,workshop,lancamento,palestra,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'capacidade_maxima' => 'required|integer|min:1',
            'valor_inscricao' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagem')) {
            if ($evento->imagem) {
                Storage::disk('public')->delete($evento->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        if ($validated['capacidade_maxima'] != $evento->capacidade_maxima) {
            $diferenca = $validated['capacidade_maxima'] - $evento->capacidade_maxima;
            $validated['vagas_disponiveis'] = $evento->vagas_disponiveis + $diferenca;
        }

        $evento->update($validated);

        return redirect()->route('evento')->with('success', 'Evento atualizado com sucesso!');
    }

    public function deletarEvento(Eventos $evento)
    {
        if ($evento->id_artesan !== auth()->id()) {
            abort(403);
        }

        if ($evento->imagem) {
            Storage::disk('public')->delete($evento->imagem);
        }

        $evento->inscricoes()->delete();
        $evento->delete();

        return redirect()->route('evento')->with('success', 'Evento removido com sucesso!');
    }
}
