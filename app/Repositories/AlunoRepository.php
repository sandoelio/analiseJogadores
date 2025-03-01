<?php

namespace App\Repositories;

use App\Models\Aluno;

class AlunoRepository
{
    public function firstOrCreate(array $dados)
    {
        return Aluno::firstOrCreate($dados);
    }

    public function getAll()
    {
        return Aluno::all();
    }

    public function getById($id)
    {
        return Aluno::with('analises')->findOrFail($id);
    }
}


