<?php

namespace App\Repositories;

use App\Models\Analise;

class AnaliseRepository
{
    // Método para buscar as últimas duas análises de um aluno
    public function getUltimasAnalises($alunoId)
    {
        return Analise::where('aluno_id', $alunoId)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
    }

    public function create(array $dados)
    {
        return Analise::create($dados);
    }
}


