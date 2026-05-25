<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtisanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cpf' => ['required', 'string', 'max:14'],
            'telefone' => ['required', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required' => 'O CPF é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'bio.max' => 'A biografia deve ter no máximo 1000 caracteres.',
            'foto.image' => 'A foto deve ser uma imagem.',
            'foto.mimes' => 'A foto deve ser JPEG ou PNG.',
            'foto.max' => 'A foto deve ter no máximo 2MB.',
        ];
    }
}
