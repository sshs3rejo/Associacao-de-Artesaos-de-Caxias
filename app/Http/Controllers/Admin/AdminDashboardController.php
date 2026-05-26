<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\ActivityLog;
use App\Models\Cliente;
use App\Models\Contato;
use App\Models\Eventos;
use App\Models\Fornecedores;
use App\Models\InscricoesEvento;
use App\Models\Instrutores;
use App\Models\MateriasPrimas;
use App\Models\Oficina;
use App\Models\Produto;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 60, function () {
            return [
                'produtos' => Produto::count(),
                'eventos' => Eventos::count(),
                'usuariosAdmin' => User::where('role', 'admin')->count(),
                'usuariosAtivos' => User::where('is_active', true)->count(),
                'artesos' => User::where('role', 'artisan')->count(),
                'clientes' => Cliente::count(),
                'vendas' => Vendas::count(),
                'oficinas' => Oficina::count(),
                'instrutores' => Instrutores::count(),
                'fornecedores' => Fornecedores::count(),
                'materiasPrimas' => MateriasPrimas::count(),
                'contatos' => Contato::count(),
            ];
        });

        $vendas = Vendas::with(['cliente', 'itens.produto'])->orderBy('data_venda', 'desc')->paginate(10);

        $produtosPendentes = Produto::where('is_approved', false)->with('artisan', 'categoria', 'estoque')->paginate(10);
        $eventosPendentes = Eventos::where('is_approved', false)->with('artisan')->paginate(10);

        return view('admin.dashboard', [
            'stats' => $stats,
            'vendas' => $vendas,
            'produtosPendentes' => $produtosPendentes,
            'eventosPendentes' => $eventosPendentes,
        ]);
    }

    public function inscricoes()
    {
        $inscricoes = InscricoesEvento::with(['evento', 'cliente'])
            ->orderBy('data_inscricao', 'desc')
            ->paginate(20);

        return view('admin.inscricoes', compact('inscricoes'));
    }

    public function destroyInscricao(InscricoesEvento $inscricao)
    {
        $evento = $inscricao->evento;
        $inscricao->delete();
        if ($evento) {
            $evento->incrementarVagas();
        }
        return redirect()->back()->with('success', 'Inscrição cancelada com sucesso.');
    }

    public function aprovarVenda(Vendas $venda)
    {
        $venda->update(['status_pagamento' => 'approved']);
        Cache::forget('dashboard_stats');
        ActivityLog::log('venda.aprovada', "Pagamento do pedido #{$venda->id_venda} confirmado.", $venda);
        return redirect()->back()->with('success', 'Pagamento do pedido #' . $venda->id_venda . ' confirmado com sucesso!');
    }

    public function aprovarProduto(Produto $produto)
    {
        $produto->update(['is_approved' => true]);
        Cache::forget('dashboard_stats');
        ActivityLog::log('produto.aprovado', "Produto \"{$produto->nome}\" aprovado.", $produto);
        return redirect()->back()->with('success', 'Produto "' . $produto->nome . '" aprovado com sucesso e publicado na vitrine!');
    }

    public function rejeitarProduto(Produto $produto)
    {
        $produto->update(['is_approved' => false]);
        Cache::forget('dashboard_stats');
        ActivityLog::log('produto.rejeitado', "Produto \"{$produto->nome}\" rejeitado.", $produto);
        return redirect()->back()->with('success', 'Produto "' . $produto->nome . '" foi rejeitado.');
    }

    public function aprovarEvento(Eventos $evento)
    {
        $evento->update(['is_approved' => true]);
        Cache::forget('dashboard_stats');
        Cache::forget('eventos_publicos');
        ActivityLog::log('evento.aprovado', "Evento \"{$evento->nome}\" aprovado.", $evento);
        return redirect()->back()->with('success', 'Evento "' . $evento->nome . '" aprovado com sucesso e publicado na agenda!');
    }

    public function rejeitarEvento(Eventos $evento)
    {
        $evento->update(['is_approved' => false]);
        Cache::forget('dashboard_stats');
        Cache::forget('eventos_publicos');
        ActivityLog::log('evento.rejeitado', "Evento \"{$evento->nome}\" rejeitado.", $evento);
        return redirect()->back()->with('success', 'Evento "' . $evento->nome . '" foi rejeitado.');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(SettingRequest $request)
    {
        foreach ($request->validated() as $key => $value) {
            Setting::setValue($key, $value);
        }

        Cache::forget('settings_all');

        ActivityLog::log('settings.atualizadas', 'Configurações da associação atualizadas.');

        return redirect()->back()->with('success', 'Configurações da Associação atualizadas com sucesso!');
    }

    public function activityLog(Request $request)
    {
        $query = ActivityLog::with('user', 'subject')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(30)->withQueryString();

        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity-log', compact('logs', 'actions'));
    }
}
