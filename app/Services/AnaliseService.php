<?php

namespace App\Services;

use App\Repositories\AnaliseRepository;

class AnaliseService {
    protected $analiseRepository;

    public function __construct(AnaliseRepository $analiseRepository) {
        $this->analiseRepository = $analiseRepository;
    }

    public function getUltimasAnalises($alunoId) {
        return $this->analiseRepository->getUltimasAnalises($alunoId);
    }
}

