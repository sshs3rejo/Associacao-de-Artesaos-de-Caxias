<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estoques;
use App\Models\Vendas;
use App\Models\Produto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Construtor vazio ou removido, já que não temos mais dependências a injetar.
    public function __construct() {}

    public function store(Request $request)
    {
        $rules = [
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.id_produto' => ['required', 'exists:produto,id_produto'],
            'itens.*.quantidade' => ['required', 'integer', 'min:1'],
        ];

        $user = $request->user();

        if (!$user) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
            $rules['guest_phone'] = ['nullable', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        try {
            return DB::transaction(function () use ($validated, $user, $request) {
                if ($user) {
                    $cliente = Cliente::firstOrCreate(
                        ['email' => $user->email],
                        [
                            'nome' => $user->name,
                            'telefone' => $user->artisanProfile?->phone ?? null,
                        ]
                    );
                } else {
                    $cliente = Cliente::firstOrCreate(
                        ['email' => $validated['guest_email']],
                        [
                            'nome' => $validated['guest_name'],
                            'telefone' => $validated['guest_phone'] ?? null,
                        ]
                    );
                }

                $valorTotal = 0;
                $itensData = [];
                $mpItens = [];

                foreach ($validated['itens'] as $item) {
                    $produto = Produto::findOrFail($item['id_produto']);

                    $estoque = Estoques::where('id_produto', $item['id_produto'])->lockForUpdate()->first();
                    if (!$estoque || $estoque->quantidade < $item['quantidade']) {
                        throw new \Exception("Estoque insuficiente para {$produto->nome}");
                    }

                    $subtotal = $produto->preco * $item['quantidade'];
                    $valorTotal += $subtotal;

                    $itensData[] = [
                        'id_produto' => $produto->id_produto,
                        'quantidade' => $item['quantidade'],
                        'preco_unitario' => $produto->preco,
                    ];

                    $mpItens[] = [
                        'id' => $produto->id_produto,
                        'nome' => $produto->nome,
                        'quantidade' => $item['quantidade'],
                        'preco' => $produto->preco,
                    ];
                }

                $venda = Vendas::create([
                    'id_cliente' => $cliente->id_cliente,
                    'data_venda' => now(),
                    'valor_total' => $valorTotal,
                    'mp_status' => 'pending', // We keep this as pending until WhatsApp confirmation
                ]);

                foreach ($itensData as $item) {
                    $venda->itens()->create($item);
                    
                    // Baixa de estoque automática (Simulado/WhatsApp)
                    Estoques::where('id_produto', $item['id_produto'])
                        ->decrement('quantidade', $item['quantidade']);
                }

                // Em vez de chamar o Mercado Pago, retornamos sucesso imediato
                return response()->json([
                    'success' => true,
                    'venda_id' => $venda->id_venda,
                    'valor_total' => $valorTotal,
                    'redirect_url' => route('checkout.success', $venda->id_venda),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function success(Vendas $venda)
    {
        $venda->load('itens.produto', 'cliente');
        return view('checkout.success', compact('venda'));
    }

    public function cancel(Vendas $venda)
    {
        $venda->load('itens.produto', 'cliente');
        return view('checkout.cancel', compact('venda'));
    }
}
