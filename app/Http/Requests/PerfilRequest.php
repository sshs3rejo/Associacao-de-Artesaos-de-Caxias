<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelLegends\PtBrValidator\Rules\CelularComDdd;

class PerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', new CelularComDdd],
            'specialty' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', new CelularComDdd],
            'is_public' => ['nullable', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'phone.celular_com_ddd' => 'Telefone inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
            'whatsapp.celular_com_ddd' => 'WhatsApp inválido. Use o formato (XX) XXXXX-XXXX apenas com números.',
            'bio.max' => 'A biografia deve ter no máximo 1000 caracteres.',
            'profile_photo.image' => 'A foto deve ser uma imagem.',
            'profile_photo.max' => 'A foto deve ter no máximo 2MB.',
        ];
    }
}
