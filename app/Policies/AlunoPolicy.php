<?php

namespace App\Policies;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlunoPolicy
{
    use HandlesAuthorization;

    /**
     * Usuário só pode editar se pertencer à mesma instituição.
     */
    public function update(User $user, Aluno $aluno): bool
    {
        return $user->instituicao_id === $aluno->instituicao_id;
    }

    /**
     * Usuário só pode excluir se pertencer à mesma instituição.
     */
    public function delete(User $user, Aluno $aluno): bool
    {
        return $user->instituicao_id === $aluno->instituicao_id;
    }
}
