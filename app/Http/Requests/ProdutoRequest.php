<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'quantidade' => 'required|integer|min:0',
            'id_artesan' => 'nullable|exists:users,id',
            'mostrar_artesao' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório.',
            'preco.required' => 'O preço é obrigatório.',
            'preco.min' => 'O preço não pode ser negativo.',
            'id_categoria.required' => 'Selecione uma categoria.',
            'id_categoria.exists' => 'A categoria selecionada é inválida.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
        ];
    }
}
