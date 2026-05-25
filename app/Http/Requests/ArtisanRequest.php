<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelLegends\PtBrValidator\Rules\CelularComDdd;
use LaravelLegends\PtBrValidator\Rules\Cpf;

class ArtisanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cpf' => ['required', new Cpf],
            'telefone' => ['required', new CelularComDdd],
            'bio' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.cpf' => 'CPF inválido. Verifique os números e digite novamente.',
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
            'bio.max' => 'A biografia deve ter no máximo 1000 caracteres.',
            'foto.image' => 'A foto deve ser uma imagem.',
            'foto.mimes' => 'A foto deve ser JPEG ou PNG.',
            'foto.max' => 'A foto deve ter no máximo 2MB.',
        ];
    }
}
