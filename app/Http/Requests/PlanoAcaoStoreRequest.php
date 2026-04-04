<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanoAcaoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string', 'max:2000'],
            'prioridade' => ['required', 'in:baixa,media,alta'],
            'status' => ['required', 'in:aberto,em_andamento,concluido,vencido'],
            'prazo' => ['nullable', 'date'],
        ];
    }
}
