<?php

namespace App\Services;

use App\Repositories\AlunoRepository;
use App\Repositories\AnaliseRepository;


class AlunoService
{
    protected $alunoRepository;
    protected $analiseRepository;

    public function __construct(AlunoRepository $alunoRepository, AnaliseRepository $analiseRepository)
    {
        $this->alunoRepository = $alunoRepository;
        $this->analiseRepository = $analiseRepository;
    }

    // Método para armazenar aluno e análise
    public function storeAlunoComAnalise(array $dados)
    {
        // Evita duplicação do aluno
        $aluno = $this->alunoRepository->firstOrCreate(['nome' => $dados['nome']]);

        // Criação da análise associada ao aluno
        $this->analiseRepository->create([
            'aluno_id' => $aluno->id,
            'arremesso' => $dados['arremesso'],
            'passe' => $dados['passe'],
            'marcacao' => $dados['marcacao'],
            'finalizacao' => $dados['finalizacao'],
            'jogada' => $dados['jogada'],
            'dominio' => $dados['dominio'],
        ]);
    }
}
