<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriasProdutos;
use App\Models\Eventos;
use App\Models\InscricoesEvento;
use App\Models\Instrutores;
use App\Models\Produto;
use App\Models\User;
use App\Models\Vendas;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'produtos' => Produto::count(),
            'eventos' => Eventos::count(),
            'categorias' => CategoriasProdutos::count(),
            'usuariosAdmin' => User::where('role', 'admin')->count(),
            'usuariosAtivos' => User::where('is_active', true)->count(),
        ];

        $vendas = Vendas::with(['cliente'])->orderBy('data_venda', 'desc')->paginate(10);

        $categorias = CategoriasProdutos::orderBy('nome_categoria')->get();
        $instrutores = Instrutores::orderBy('nome')->get();
        $tiposEvento = ['feira', 'exposicao', 'workshop', 'lancamento', 'palestra', 'outro'];
        $statusEvento = ['planejado', 'confirmado', 'em_andamento', 'concluido', 'cancelado'];

        // Produtos aguardando aprovação
        $produtosPendentes = Produto::where('is_approved', false)->with('artisan')->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'vendas' => $vendas,
            'categorias' => $categorias,
            'instrutores' => $instrutores,
            'tiposEvento' => $tiposEvento,
            'statusEvento' => $statusEvento,
            'produtosPendentes' => $produtosPendentes,
        ]);
    }

    public function inscricoes()
    {
        $inscricoes = InscricoesEvento::with(['evento', 'cliente'])
            ->orderBy('data_inscricao', 'desc')
            ->paginate(20);

        return view('admin.inscricoes', compact('inscricoes'));
    }

    public function aprovarVenda(Vendas $venda)
    {
        $venda->update(['mp_status' => 'approved']);
        return redirect()->back()->with('success', 'Pagamento do pedido #' . $venda->id_venda . ' confirmado com sucesso!');
    }

    public function aprovarProduto(Produto $produto)
    {
        $produto->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Produto "' . $produto->nome . '" aprovado com sucesso e publicado na vitrine!');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_short' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        file_put_contents(storage_path('app/settings.json'), json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Configurações da Associação atualizadas com sucesso!');
    }
}
