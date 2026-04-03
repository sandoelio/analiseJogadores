<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoUpdateHabilidadeRequest extends FormRequest
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
            'aluno_id' => 'required|exists:alunos,id',
            'data_nascimento' => 'sometimes|nullable|date',
            'sexo' => 'sometimes|nullable|in:Masculino,Feminino',
            'idade' => 'sometimes|nullable|integer|min:0',
            'telefone' => 'sometimes|nullable|digits_between:10,11',
            'arremesso' => 'sometimes|nullable|integer|between:0,10',
            'passe' => 'sometimes|nullable|integer|between:0,10',
            'marcacao' => 'sometimes|nullable|integer|between:0,10',
            'bandeja' => 'sometimes|nullable|integer|between:0,10',
            'rebote' => 'sometimes|nullable|integer|between:0,10',
            'dominio' => 'sometimes|nullable|integer|between:0,10',
            'potencia_mmss' => 'sometimes|nullable|numeric|min:0',
            'capacidade_aerobica' => 'sometimes|nullable|numeric|min:0',
            'agilidade' => 'sometimes|nullable|numeric|min:0',
            'flexibilidade' => 'sometimes|nullable|numeric|min:0',
            'potencia_mmii' => 'sometimes|nullable|numeric|min:0',
            'massa_corporal_kg' => 'sometimes|nullable|numeric|min:0',
            'gordura_pct' => 'sometimes|nullable|numeric|min:0',
            'massa_magra_pct' => 'sometimes|nullable|numeric|min:0',
            'envergadura_cm' => 'sometimes|nullable|numeric|min:0',
            'imc' => 'sometimes|nullable|numeric|min:0',
            'problema_saude' => 'sometimes|nullable|boolean',
            'problema_saude_descricao' => 'sometimes|nullable|required_if:problema_saude,1|string|max:255',
            'atestado_valido' => 'sometimes|nullable|boolean',
            'data_atestado' => 'sometimes|nullable|required_if:atestado_valido,1|date',
            'usa_medicacao' => 'sometimes|nullable|boolean',
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
