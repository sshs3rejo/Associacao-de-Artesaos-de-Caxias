<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estoques;
use App\Models\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
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

            foreach ($validated['itens'] as $item) {
                $produto = \App\Models\Produto::findOrFail($item['id_produto']);

                $estoque = Estoques::where('id_produto', $item['id_produto'])->first();
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

                $estoque->decrement('quantidade', $item['quantidade']);
            }

            $venda = Vendas::create([
                'id_cliente' => $cliente->id_cliente,
                'data_venda' => now(),
                'valor_total' => $valorTotal,
            ]);

            foreach ($itensData as $item) {
                $venda->itens()->create($item);
            }

            return redirect()->route('home')
                ->with('success', "Pedido #{$venda->id_venda} realizado com sucesso! Total: R$ " . number_format($valorTotal, 2, ',', '.'));
        });
    }
}
