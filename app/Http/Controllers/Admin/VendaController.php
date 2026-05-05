<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendas;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    /**
     * Exibe o histórico de compras (vendas)
     */
    public function index()
    {
        // Carrega as vendas com os clientes e itens (se houver relacionamento)
        $vendas = Vendas::with(['cliente'])->orderBy('data_venda', 'desc')->paginate(10);
        
        return view('admin.vendas.index', compact('vendas'));
    }
}
