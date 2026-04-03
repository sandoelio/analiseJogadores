<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $telefone = preg_replace('/\D+/', '', (string) $this->input('telefone', ''));

        $this->merge([
            'telefone' => $telefone !== '' ? $telefone : null,
            'problema_saude' => $this->normalizarBooleano('problema_saude'),
            'atestado_valido' => $this->normalizarBooleano('atestado_valido'),
            'usa_medicacao' => $this->normalizarBooleano('usa_medicacao'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|in:Masculino,Feminino,M,F',
            'telefone' => 'nullable|digits_between:10,11',
            'arremesso' => 'required|integer|between:0,10',
            'passe' => 'required|integer|between:0,10',
            'marcacao' => 'required|integer|between:0,10',
            'bandeja' => 'required|integer|between:0,10',
            'rebote' => 'required|integer|between:0,10',
            'dominio' => 'required|integer|between:0,10',
            'potencia_mmss' => 'required|numeric|min:0',
            'capacidade_aerobica' => 'required|numeric|min:0',
            'agilidade' => 'required|numeric|min:0',
            'flexibilidade' => 'required|numeric|min:0',
            'potencia_mmii' => 'required|numeric|min:0',
            'massa_corporal_kg' => 'required|numeric|min:0',
            'gordura_pct' => 'required|numeric|min:0',
            'massa_magra_pct' => 'required|numeric|min:0',
            'envergadura_cm' => 'required|numeric|min:0',
            'imc' => 'required|numeric|min:0',
            'problema_saude' => 'nullable|boolean',
            'atestado_valido' => 'nullable|boolean',
            'usa_medicacao' => 'nullable|boolean',
        ];
    }

    private function normalizarBooleano(string $campo): ?int
    {
        $valor = $this->input($campo);

        if ($valor === null || $valor === '') {
            return null;
        }

        if (in_array($valor, [true, 1, '1', 'true'], true)) {
            return 1;
        }

        if (in_array($valor, [false, 0, '0', 'false'], true)) {
            return 0;
        }

        return null;
    }
}
