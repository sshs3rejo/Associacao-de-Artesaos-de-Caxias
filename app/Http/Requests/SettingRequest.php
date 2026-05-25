<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_short' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'description' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da associação é obrigatório.',
            'name_short.required' => 'O nome abreviado é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'whatsapp.required' => 'O WhatsApp é obrigatório.',
            'address.required' => 'O endereço é obrigatório.',
            'instagram.url' => 'Informe uma URL válida para o Instagram.',
            'facebook.url' => 'Informe uma URL válida para o Facebook.',
            'description.required' => 'A descrição é obrigatória.',
            'description.max' => 'A descrição deve ter no máximo 1000 caracteres.',
        ];
    }
}
