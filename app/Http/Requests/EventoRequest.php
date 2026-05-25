<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_evento' => 'required|in:feira,exposicao,workshop,lancamento,palestra,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'capacidade_maxima' => 'required|integer|min:0',
            'valor_inscricao' => 'required|numeric|min:0',
            'status' => 'sometimes|required|in:planejado,confirmado,em_andamento,concluido,cancelado',
            'nome_instrutor' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do evento é obrigatório.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'tipo_evento.required' => 'O tipo de evento é obrigatório.',
            'tipo_evento.in' => 'Selecione um tipo de evento válido.',
            'data_inicio.required' => 'A data de início é obrigatória.',
            'data_inicio.date' => 'Informe uma data de início válida.',
            'data_fim.required' => 'A data de fim é obrigatória.',
            'data_fim.date' => 'Informe uma data de fim válida.',
            'data_fim.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'local.required' => 'O local é obrigatório.',
            'capacidade_maxima.required' => 'A capacidade máxima é obrigatória.',
            'capacidade_maxima.integer' => 'A capacidade máxima deve ser um número inteiro.',
            'valor_inscricao.required' => 'O valor da inscrição é obrigatório.',
            'valor_inscricao.numeric' => 'O valor da inscrição deve ser numérico.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Selecione um status válido.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser JPEG, PNG, JPG ou GIF.',
            'imagem.max' => 'A imagem deve ter no máximo 2MB.',
        ];
    }
}
