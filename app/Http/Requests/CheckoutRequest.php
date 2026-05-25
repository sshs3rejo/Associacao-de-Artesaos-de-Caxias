<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.id' => ['required', 'exists:produto,id_produto'],
            'itens.*.quantidade' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'itens.required' => 'O carrinho está vazio.',
            'itens.min' => 'Adicione pelo menos um item ao carrinho.',
            'itens.*.id.required' => 'Produto inválido.',
            'itens.*.id.exists' => 'Produto não encontrado.',
            'itens.*.quantidade.required' => 'A quantidade é obrigatória.',
            'itens.*.quantidade.min' => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}
