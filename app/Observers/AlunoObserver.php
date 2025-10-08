<?php

namespace App\Observers;

use App\Models\Aluno;
use App\Models\AlunoHistory;
use Illuminate\Support\Facades\Auth;

class AlunoObserver
{
    protected function createHistory(Aluno $aluno, string $evento, $dados = null, $changedBy = null, $timestamp = null)
    {
        AlunoHistory::create([
            'aluno_id'   => $aluno->id,
            'evento'     => $evento,
            'dados'      => $dados !== null ? $dados : null,
            'changed_by' => $changedBy ?? Auth::id(),
            'created_at' => $timestamp ?? now(),
            'updated_at' => $timestamp ?? now(),
        ]);
    }

    // chamado automaticamente pelo Eloquent quando o aluno é criado
    public function created(Aluno $aluno)
    {
        $dados = $aluno->only(['nome', 'matricula', 'instituicao_id', 'user_id']);
        $this->createHistory($aluno, 'created', $dados, null, $aluno->created_at);
    }

    // chamado automaticamente quando o aluno é salvo e houve mudança
    public function updated(Aluno $aluno)
    {
        $changes = $aluno->getChanges(); // só campos alterados
        // remover campos irrelevantes
        unset($changes['updated_at']);
        if (empty($changes)) {
            return;
        }

        $original = $aluno->getOriginal();

        // construir diff: [campo => ['antes'=>..., 'depois'=>...]]
        $diff = [];
        foreach ($changes as $k => $novo) {
            $diff[$k] = [
                'antes' => $original[$k] ?? null,
                'depois' => $novo,
            ];
        }

        $dados = [
            'antes' => array_intersect_key($original, $changes),
            'depois' => $changes,
            'diff' => $diff,
        ];

        $this->createHistory($aluno, 'updated', $dados, null, $aluno->updated_at);
    }

    /**
     * Registrar evento de análise (chamado manualmente onde a análise é criada)
     * $payload pode conter 'tecnicos', 'fisicos', 'composicao', 'saude', etc.
     */
    public function recordAnalise(Aluno $aluno, array $payload, $changedBy = null, $timestamp = null)
    {
        $this->createHistory($aluno, 'analise_created', $payload, $changedBy, $timestamp);
    }
}
